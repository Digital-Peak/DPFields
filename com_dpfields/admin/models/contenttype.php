<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

class DPFieldsModelContentType extends JModelAdmin
{
	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			if ($record->state != -2) {
				return false;
			}

			return JFactory::getUser()->authorise('core.delete', 'com_dpfields.contenttype.' . (int)$record->id);
		}

		return false;
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing article.
		if (!empty($record->id)) {
			return $user->authorise('core.edit.state', 'com_dpfields.contenttype.' . (int)$record->id);
		}

		// Default to component settings if neither article nor category known.
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
	}

	public function getTable($type = 'ContentType', $prefix = 'DPFieldsTable', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer $pk The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			// Convert the params field to an array.
			$registry      = new Registry($item->attribs);
			$item->attribs = $registry->toArray();
		}

		return $item;
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_dpfields.contenttype', 'contenttype', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}

		$jinput = JFactory::getApplication()->input;

		/*
		 * The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		 * The back end uses id so we use that the rest of the time and set it to 0 by default.
		 */
		$id = $jinput->get('c_id', $jinput->get('id', 0));

		// Determine correct permissions to check.
		if ($this->getState('contenttype.id')) {
			$id = $this->getState('contenttype.id');
		}

		$user = JFactory::getUser();

		// Check for existing article.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_dpfields.contenttype.' . (int)$id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_dpfields'))
		) {
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an article you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		if ($id) {
			$form->setFieldAttribute('name', 'disabled', 'true');
			$form->setFieldAttribute('name', 'filter', 'unset');
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app  = JFactory::getApplication();
		$data = $app->getUserState('com_dpfields.edit.contenttype.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Pre-select some filters (Status, Category, Language, Access) in edit form if those have been selected in Article Manager: Articles
			if ($this->getState('contenttype.id') == 0) {
				$filters = (array)$app->getUserState('com_dpfields.contenttypes.filter');
				$data->set(
					'state',
					$app->input->getInt(
						'state',
						((isset($filters['state']) && $filters['state'] !== '') ? $filters['state'] : null)
					)
				);
			}
		}

		// If there are params fieldsets in the form it will fail with a registry object
		if (isset($data->params) && $data->params instanceof Registry) {
			$data->params = $data->params->toArray();
		}

		$this->preprocessData('com_dpfields.contenttype', $data);

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

		if (isset($data['created_by_alias'])) {
			$data['created_by_alias'] = $filter->clean($data['created_by_alias'], 'TRIM');
		}

		// Alter the title for save as copy
		if ($input->get('task') == 'save2copy') {
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['title'] == $origTable->title) {
				list($title, $name) = $this->generateNewDPTitle($data['name'], $data['title']);
				$data['title'] = $title;
				$data['name']  = $name;
			} else {
				if ($data['name'] == $origTable->name) {
					$data['name'] = '';
				}
			}

			$data['state'] = 0;
		}

		// Automatic handling of name for empty fields
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (!isset($data['id']) || (int)$data['id'] == 0)) {
			if ($data['name'] == null) {
				if (JFactory::getConfig()->get('unicodeslugs') == 1) {
					$data['name'] = JFilterOutput::stringURLUnicodeSlug($data['title']);
				} else {
					$data['name'] = JFilterOutput::stringURLSafe($data['title']);
				}

				$table = JTable::getInstance('ContentType', 'DPFieldsTable');

				if ($table->load(array('name' => $data['name']))) {
					$msg = JText::_('COM_CONTENT_SAVE_WARNING');
				}

				list($title, $name) = $this->generateNewDPTitle($data['name'], $data['title']);
				$data['name'] = $name;

				if (isset($msg)) {
					JFactory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}

		$success = parent::save($data);

		if ($success && empty($data['id'])) {
			$id = $this->getState($this->getName() . '.id');

			$contentType = $this->getItem($id);

			// Create default calendar
			JTable::addIncludePath(JPATH_LIBRARIES . '/joomla/database/table');
			$category              = JTable::getInstance('Category');
			$category->extension   = 'com_dpfields.' . $contentType->name;
			$category->title       = 'Uncategorised';
			$category->alias       = 'uncategorised';
			$category->description = '';
			$category->published   = 1;
			$category->access      = 1;
			$category->params      = '{"category_layout":"","image":""}';
			$category->metadata    = '{"author":"","robots":""}';
			$category->language    = '*';
			$category->setLocation(1, 'last-child');
			$category->store(true);
			$category->rebuildPath($category->id);
		}

		return $success;
	}

	protected function generateNewDPTitle($name, $title)
	{
		// Alter the title & name
		$table = $this->getTable();

		while ($table->load(array('name' => $name))) {
			$title = StringHelper::increment($title);
			$name  = StringHelper::increment($name, 'dash');
		}

		return array($title, $name);
	}

	protected function populateState()
	{
		$app = JFactory::getApplication();

		$pk = $app->input->getInt('c_id');
		$this->setState('contenttype.id', $pk);
		$this->setState('form.id', $pk);

		$return = $app->input->getVar('return', null, 'default', 'base64');

		if (!JUri::isInternal(base64_decode($return))) {
			$return = null;
		}

		$this->setState('return_page', base64_decode($return));

		$params = JComponentHelper::getParams('com_dpfields');

		if ($app->isSite()) {
			$params = $app->getParams();
		}
		$this->setState('params', $params);
	}
}
