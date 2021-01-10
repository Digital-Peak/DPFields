<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

class DPFieldsModelEntity extends JModelAdmin
{
	protected $text_prefix = 'COM_DPFIELDS';
	public $typeAlias = 'com_dpfields.entity';
	protected $associationsContext = 'com_dpfields.entity';

	protected function batchCopy($value, $pks, $contexts)
	{
		$categoryId = (int)$value;

		$newIds = array();

		if (!$this->checkCategoryId($categoryId)) {
			return false;
		}

		// Parent exists so we let's proceed
		while (!empty($pks)) {
			// Pop the first ID off the stack
			$pk = array_shift($pks);

			$this->table->reset();

			// Check that the row actually exists
			if (!$this->table->load($pk)) {
				if ($error = $this->table->getError()) {
					// Fatal error
					$this->setError($error);

					return false;
				} else {
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Alter the title & alias
			$data               = $this->generateNewTitle($categoryId, $this->table->alias, $this->table->title);
			$this->table->title = $data['0'];
			$this->table->alias = $data['1'];

			// Reset the ID because we are making a copy
			$this->table->id = 0;

			// Reset hits because we are making a copy
			$this->table->hits = 0;

			// Unpublish because we are making a copy
			$this->table->state = 0;

			// New category ID
			$this->table->catid = $categoryId;

			// TODO: Deal with ordering?
			// $table->ordering	= 1;

			// Get the featured state
			$featured = $this->table->featured;

			// Check the row.
			if (!$this->table->check()) {
				$this->setError($this->table->getError());

				return false;
			}

			$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);

			// Store the row.
			if (!$this->table->store()) {
				$this->setError($this->table->getError());

				return false;
			}

			// Get the new item ID
			$newId = $this->table->get('id');

			// Add the new ID to the array
			$newIds[$pk] = $newId;

			// Check if the entity was featured and update the #__content_frontpage table
			if ($featured == 1) {
				$db    = $this->getDbo();
				$query = $db->getQuery(true)
					->insert($db->quoteName('#__content_frontpage'))
					->values($newId . ', 0');
				$db->setQuery($query);
				$db->execute();
			}
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}

	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			if ($record->state != -2) {
				return false;
			}

			return JFactory::getUser()->authorise('core.delete', 'com_dpfields.entity.' . (int)$record->id);
		}

		return false;
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing entity.
		if (!empty($record->id)) {
			return $user->authorise('core.edit.state', 'com_dpfields.entity.' . (int)$record->id);
		}

		// New entity, so check against the category.
		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_dpfields.category.' . (int)$record->catid);
		}

		// Default to component settings if neither entity nor category known.
		return parent::canEditState();
	}

	protected function prepareTable($table)
	{
		// Set the publish date to now
		if ($table->state == 1 && (int)$table->publish_up == 0) {
			$table->publish_up = JFactory::getDate()->toSql();
		}

		if ($table->state == 1 && intval($table->publish_down) == 0) {
			$table->publish_down = $this->getDbo()->getNullDate();
		}

		// Increment the content version number.
		$table->version++;

		// Reorder the entites within the category so the new entity is first
		if (empty($table->id)) {
			$table->reorder('catid = ' . (int)$table->catid . ' AND state >= 0');
		}
	}

	public function getTable($type = 'Entity', $prefix = 'DPFieldsTable', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			// Convert the params field to an array.
			$registry      = new Registry($item->attribs);
			$item->attribs = $registry->toArray();

			// Convert the metadata field to an array.
			$registry       = new Registry($item->metadata);
			$item->metadata = $registry->toArray();

			if (!empty($item->id)) {
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_dpfields.entity');
			}
		}

		// Load associated content items
		$assoc = JLanguageAssociations::isEnabled();

		if ($assoc) {
			$item->associations = array();

			if ($item->id != null) {
				$associations = JLanguageAssociations::getAssociations($this->getState('entity.context'), '#__dpfields_entities', 'com_dpfields.entity', $item->id);

				foreach ($associations as $tag => $association) {
					$item->associations[$tag] = $association->id;
				}
			}
		}

		return $item;
	}

	public function getForm($data = array(), $loadData = true)
	{
		JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_dpfields/models/forms');

		$jinput = JFactory::getApplication()->input;

		$id      = $jinput->get('e_id', $jinput->get('id', 0));
		$context = $this->getState('entity.context');

		if (!$context && $item = $this->getItem($id)) {
			// Probably on front end editing
			$contentType = $this->getContentType($item->content_type_id);
			if ($contentType->id) {
				$context = 'com_dpfields.' . $contentType->name;
				$this->setState('entity.context', $context);
			}
		}

		// Get the form.
		$form = $this->loadForm($context, 'entity', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('entity.id')) {
			$id = $this->getState('entity.id');

			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');

			// Existing record. Can only edit own entities in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		} else {
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// Check for existing entity.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_dpfields.entity.' . (int)$id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_dpfields'))
		) {
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an entity you can edit.
			$form->setFieldAttribute('featured', 'filter', 'unset');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		// Prevent messing with entity language and category when editing existing entity with associations
		$app   = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();

		// Check if entity is associated
		if ($this->getState('entity.id') && $app->isClient('site') && $assoc) {
			$associations = JLanguageAssociations::getAssociations('com_dpfields', '#__dpfields_entites', 'com_dpfields.entity', $id);

			// Make fields read only
			if (!empty($associations)) {
				$form->setFieldAttribute('language', 'readonly', 'true');
				$form->setFieldAttribute('catid', 'readonly', 'true');
				$form->setFieldAttribute('language', 'filter', 'unset');
				$form->setFieldAttribute('catid', 'filter', 'unset');
			}
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_dpfields.edit.entity.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Pre-select some filters (Status, Category, Language, Access) in edit form if those have been selected in Article Manager: Articles
			if ($this->getState('entity.id') == 0) {
				$filters = (array)$app->getUserState('com_dpfields.entities.filter');
				$data->set(
					'state',
					$app->input->getInt(
						'state',
						((isset($filters['published']) && $filters['published'] !== '') ? $filters['published'] : null)
					)
				);
				$data->set('catid', $app->input->getInt('catid', (!empty($filters['category_id']) ? $filters['category_id'] : null)));
				$data->set('language', $app->input->getString('language', (!empty($filters['language']) ? $filters['language'] : null)));
				$data->set('access',
					$app->input->getInt('access', (!empty($filters['access']) ? $filters['access'] : JFactory::getConfig()->get('access')))
				);
			}
		}

		// If there are params fieldsets in the form it will fail with a registry object
		if (isset($data->params) && $data->params instanceof Registry) {
			$data->params = $data->params->toArray();
		}

		$this->preprocessData('com_dpfields.entity', $data);

		return $data;
	}

	public function validate($form, $data, $group = null)
	{
		// Don't allow to change the users if not allowed to access com_users.
		if (($data['created_by'] || $data['modified_by']) && !JFactory::getUser()->authorise('core.manage', 'com_users')) {
			if (isset($data['created_by'])) {
				unset($data['created_by']);
			}

			if (isset($data['modified_by'])) {
				unset($data['modified_by']);
			}
		}

		return parent::validate($form, $data, $group);
	}

	public function save($data)
	{
		$input  = JFactory::getApplication()->input;
		$filter = JFilterInput::getInstance();

		if (isset($data['metadata']) && isset($data['metadata']['author'])) {
			$data['metadata']['author'] = $filter->clean($data['metadata']['author'], 'TRIM');
		}

		if (isset($data['created_by_alias'])) {
			$data['created_by_alias'] = $filter->clean($data['created_by_alias'], 'TRIM');
		}

		JLoader::register('CategoriesHelper', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/categories.php');

		// Cast catid to integer for comparison
		$catid = (int)$data['catid'];

		// Check if New Category exists
		if ($catid > 0) {
			$catid = CategoriesHelper::validateCategoryId($data['catid'], 'com_dpfields');
		}

		// Save New Categoryg
		if ($catid == 0 && $this->canCreateCategory()) {
			$table              = array();
			$table['title']     = $data['catid'];
			$table['parent_id'] = 1;
			$table['extension'] = 'com_dpfields';
			$table['language']  = $data['language'];
			$table['published'] = 1;

			// Create new category and get catid back
			$data['catid'] = CategoriesHelper::createCategory($table);
		}

		// Alter the title for save as copy
		if ($input->get('task') == 'save2copy') {
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('e_id', $data['id']));

			if ($data['title'] == $origTable->title) {
				list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
				$data['title'] = $title;
				$data['alias'] = $alias;
			} else {
				if ($data['alias'] == $origTable->alias) {
					$data['alias'] = '';
				}
			}

			$data['state'] = 0;
		}

		// Automatic handling of alias for empty fields
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (!isset($data['id']) || (int)$data['id'] == 0)) {
			if ($data['alias'] == null) {
				if (JFactory::getConfig()->get('unicodeslugs') == 1) {
					$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['title']);
				} else {
					$data['alias'] = JFilterOutput::stringURLSafe($data['title']);
				}

				$table = JTable::getInstance('Content', 'JTable');

				if ($table->load(array('alias' => $data['alias'], 'catid' => $data['catid']))) {
					$msg = JText::_('COM_CONTENT_SAVE_WARNING');
				}

				list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
				$data['alias'] = $alias;

				if (isset($msg)) {
					JFactory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}

		if (empty($data['id'])) {
			$contentType             = $this->getContentType(array('name' => str_replace('com_dpfields.', '', $this->getState('entity.context'))));
			$data['content_type_id'] = $contentType->id;
		} else {
			$item        = $this->getItem((int)$data['id']);
			$contentType = $this->getContentType((int)$item->content_type_id);
			unset($data['content_type_id']);
		}

		// Change the name that the events are fired with the right name
		$originalName = $this->name;
		$this->name   = $contentType->name;

		$success = parent::save($data);

		$this->name = $originalName;
		if ($success) {
			if (isset($data['featured'])) {
				$this->featured($this->getState($contentType->name . '.id'), $data['featured']);
			}

			JFactory::getApplication()->setUserState('dpfields.entity.id', $this->getState($contentType->name . '.id'));

			return true;
		}

		return false;
	}

	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array)$pks;
		$pks = ArrayHelper::toInteger($pks);

		if (empty($pks)) {
			$this->setError(JText::_('COM_CONTENT_NO_ITEM_SELECTED'));

			return false;
		}

		try {
			$db    = $this->getDbo();
			$query = $db->getQuery(true)
				->update($db->quoteName('#__dpfields_entities'))
				->set('featured = ' . (int)$value)
				->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();
		} catch (Exception $e) {
			$this->setError($e->getMessage());

			return false;
		}

		$this->cleanCache();

		return true;
	}

	protected function getReorderConditions($table)
	{
		return array('catid = ' . (int)$table->catid);
	}

	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		if ($this->canCreateCategory()) {
			$form->setFieldAttribute('catid', 'allowAdd', 'true');
		}

		$form->setFieldAttribute('catid', 'extension', $this->getState('entity.context'));

		// Association content items
		if (JLanguageAssociations::isEnabled()) {
			$languages = JLanguageHelper::getContentLanguages(false, true, null, 'ordering', 'asc');

			if (count($languages) > 1) {
				$addform = new SimpleXMLElement('<form />');
				$fields  = $addform->addChild('fields');
				$fields->addAttribute('name', 'associations');
				$fieldset = $fields->addChild('fieldset');
				$fieldset->addAttribute('name', 'item_associations');
				$fieldset->addAttribute('label', JText::_('COM_DPFIELDS_ASSOCIATION'));

				foreach ($languages as $language) {
					$field = $fieldset->addChild('field');
					$field->addAttribute('name', $language->lang_code);
					$field->addAttribute('type', 'modal_entity');
					$field->addAttribute('language', $language->lang_code);
					$field->addAttribute('label', $language->title);
					$field->addAttribute('translate_label', 'false');
					$field->addAttribute('select', 'true');
					$field->addAttribute('new', 'true');
					$field->addAttribute('edit', 'true');
					$field->addAttribute('clear', 'true');
					$field->addAttribute('context', $this->getState('entity.context'));
				}

				$form->load($addform, false);
			}
		}

		parent::preprocessForm($form, $data, $group);
	}

	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_dpfields');
	}

	public function hit()
	{
		return;
	}

	private function canCreateCategory()
	{
		return false;

		return JFactory::getUser()->authorise('core.create', 'com_dpfields');
	}

	public function delete(&$pks)
	{
		$return = parent::delete($pks);

		if ($return) {
			// Now check to see if this articles was featured if so delete it from the #__content_frontpage table
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->delete($db->quoteName('#__content_frontpage'))
				->where('content_id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();
		}

		return $return;
	}

	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$pk = $app->input->getInt('e_id');
		$this->setState($this->getName() . '.id', $pk);
		$this->setState('form.id', $pk);

		$context = $app->input->get('context');
		if (!$context) {
			$contentType = $this->getContentType($app->input->getInt('c_id'));
			if ($contentType->id) {
				$context = 'com_dpfields.' . $contentType->name;
			}
		}
		$this->setState('entity.context', $context);

		$return = $app->input->get('return', null, 'default', 'base64');

		if (!JUri::isInternal(base64_decode($return))) {
			$return = null;
		}

		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params = JComponentHelper::getParams('com_dpfields');
		$this->setState('params', $params);
	}

	public function getContentType($lookup)
	{
		\JLoader::import('components.com_dpfields.tables.contenttype', JPATH_ADMINISTRATOR);
		$table = new \DPFieldsTableContentType(JFactory::getDbo());
		$table->load($lookup);

		return $table;
	}

	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}
}
