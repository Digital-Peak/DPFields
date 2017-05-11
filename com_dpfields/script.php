<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2007 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class Com_DPFieldsInstallerScript
{

	public function install($parent)
	{
	}

	public function update($parent)
	{
		$path    = JPATH_ADMINISTRATOR . '/components/com_dpfields/dpfields.xml';
		$version = null;
		if (file_exists($path)) {
			$manifest = simplexml_load_file($path);
			$version  = (string)$manifest->version;
		}
		if (empty($version)) {
			return;
		}

		$db = JFactory::getDbo();
		if (version_compare($version, '2.0.0') == -1) {
			$query = $db->getQuery(true);
			$query->select('*')->from('#__categories')->where($db->quoteName('extension') . ' like ' . $db->q('%.fields'));
			$db->setQuery($query);

			JTable::addIncludePath(JPATH_LIBRARIES . '/joomla/database/table');
			JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_fields/tables');
			JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_fields/models');

			$groupIds = array();

			// Migrate the categories
			foreach ($db->loadAssocList() as $cat) {
				// Load the table
				$category = JTable::getInstance('Category');
				$category->load($cat['id']);

				// Set up the data
				$data                = array();
				$data['asset_id']    = $category->asset_id;
				$data['context']     = str_replace('.fields', '', $category->extension);
				$data['title']       = $category->title;
				$data['note']        = $category->note;
				$data['description'] = strip_tags($category->description);
				$data['state']       = $category->published;
				$data['language']    = $category->language;
				$data['access']      = $category->access;

				// Save it trough the model
				$model = JModelLegacy::getInstance('Group', 'FieldsModel', array('ignore_request' => true));
				if (!$model->save($data)) {
					JFactory::getApplication()->enqueueMessage('Could not migrate field group ' . $category->title . ' because:' . $model->getError());
				}

				// Delete the category
				$category->delete();

				// Map the category id to the group
				$groupIds[$category->id] = $model->getState('group.id');
			}

			$query = $db->getQuery(true);
			$query->select('*')->from('#__dpfields_fields');
			$db->setQuery($query);

			// Migrate the fields
			foreach ($db->loadObjectList() as $field) {
				// Set up the data
				$data             = array();
				$data['asset_id'] = $field->asset_id;
				$data['context']  = $field->context;

				if ($field->catid && !empty($groupIds[$field->catid])) {
					$data['group_id'] = $groupIds[$field->catid];
				}

				$data['title']         = $field->title;
				$data['name']          = $field->alias;
				$data['label']         = $field->label;
				$data['default_value'] = $field->default_value;

				$type = $field->type;
				if ($type == 'gallery') {
					$type = 'dpfgallery';
				}
				$data['type'] = $type;

				$data['note']        = strip_tags($field->note);
				$data['description'] = strip_tags($field->description);
				$data['state']       = $field->state;
				$data['required']    = $field->required;
				$data['ordering']    = $field->ordering;

				$params = new \Joomla\Registry\Registry($field->params);
				$params->set('class', $field->class);
				$params->set('render_class', $field->render_class);
				$params->set('showlabel', 1);

				// No inheritance
				if ($params->get('display') == '-1') {
					$params->set('display', 2);
				}
				$data['params'] = $params->toString();

				$fieldParams = new \Joomla\Registry\Registry($field->fieldparams);

				// Calendar parameters do have changed
				if ($type == 'calendar') {
					$fieldParams = new \Joomla\Registry\Registry();
					$fieldParams->set('showtime', 0);
				}

				// Editor parameters do have changed
				if ($type == 'editor') {
					$fieldParams->set('hide', null);
				}

				// Migrate the options from repeatable to subform format
				if ($options = $fieldParams->get('options')) {
					$options = json_decode($options);

					$newOptions = new stdClass();
					foreach ($options->key as $index => $option) {
						$obj        = new stdClass();
						$obj->value = $option;
						$obj->name  = $options->value[$index];

						$newOptions->{'options' . ($index + 1)} = $obj;
					}

					$fieldParams->set('options', $newOptions);
				}

				$data['fieldparams'] = $fieldParams->toString();
				$data['language']    = $field->language;
				$data['access']      = $field->access;

				// Save the field
				$model = JModelLegacy::getInstance('Field', 'FieldsModel', array('ignore_request' => true));
				if (!$model->save($data)) {
					JFactory::getApplication()->enqueueMessage('Could not migrate field ' . $field->title . ' because:' . $model->getError());
					continue;
				}

				$id = $model->getState('field.id');

				// Set up the mapping to the assigned categories
				if ($field->assigned_cat_ids) {
					foreach (explode(',', $field->assigned_cat_ids) as $assignedCategory) {
						$query = $db->getQuery(true);
						$query->insert('#__fields_categories')->set('field_id=' . (int)$id)->set('category_id=' . $assignedCategory);
						$db->setQuery($query);
						$db->execute();
					}
				}

				// Remapp the values
				$query = $db->getQuery(true);
				$query->select('*')->from('#__dpfields_fields_values')->where('field_id=' . $field->id);
				$db->setQuery($query);

				foreach ($db->loadObjectList() as $value) {
					$query = $db->getQuery(true);
					$query->insert('#__fields_values')->set('field_id=' . (int)$id)->set('item_id=' . $value->item_id)->set('value=' . $db->quote($value->value));
					$db->setQuery($query);
					$db->execute();
				}
			}

			// Drop the tables here manually, the update script is executed before
			$this->run("DROP TABLE IF EXISTS `#__dpfields_fields`");
			$this->run("DROP TABLE IF EXISTS `#__dpfields_fields_values`");

			// Delete the system plugin
			JLoader::import('joomla.filesystem.folder');
			JFolder::delete(JPATH_PLUGINS . '/system/dpfields');
			$this->run("delete from `#__extensions` where folder = 'system' and element = 'dpfields'");

			// Activate the new plugins
			$this->run("update `#__extensions` set enabled=1 where type = 'plugin' and element = 'dpfields'");
			$this->run("update `#__extensions` set enabled=1 where type = 'plugin' and element = 'dpfgallery'");
		}
	}

	public function uninstall($parent)
	{
	}

	public function preflight($type, $parent)
	{
		// Check if the local Joomla version does fit the minimum requirement
		if (version_compare(JVERSION, '3.7') == -1) {
			JFactory::getApplication()->enqueueMessage(
				'This DPFields version does only run on Joomla 3.7 and above, please upgrade your Joomla version!', 'error');
			JFactory::getApplication()->redirect('index.php?option=com_installer&view=install');

			return false;
		}
	}

	public function postflight($type, $parent)
	{
		if ($type == 'install') {
			$this->run("update `#__extensions` set enabled=1 where type = 'plugin' and element = 'dpfields'");
			$this->run("update `#__extensions` set enabled=1 where type = 'plugin' and element = 'dpfgallery'");
		}
	}

	private function run($query)
	{
		try {
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$db->execute();
		} catch (Exception $e) {
			echo $e;
		}
	}
}
