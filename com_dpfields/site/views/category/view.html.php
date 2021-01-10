<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsViewCategory extends \DPFields\View\BaseView
{
	public function display($tpl = null)
	{
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_dpfields/models');
		$model = JModelLegacy::getInstance('Entities', 'DPFieldsModel');
		$this->setModel($model, true);

		return parent::display($tpl);
	}

	public function init()
	{
		// Set up the model
		$model = $this->getModel();
		$model->setState('filter.published', 1);

		// Get the category
		$category = $model->getCategory($this->input->getInt('id'));

		if (!$category || !$category->id) {
			throw new Exception(JText::_('JGLOBAL_CATEGORY_NOT_FOUND'), 404);
		}

		// Set the category id as filter
		$model->setState('filter.category_id', $category->id);

		// Map the description to the text field for the events
		$category->text = $category->description;

		$category->event = new stdClass();

		$this->app->triggerEvent('onContentPrepare', array($category->extension . '.categories', &$category, &$category->params, 0));

		// Fetch the event data
		$results = $this->app->triggerEvent('onContentAfterTitle', array($category->extension . '.categories', &$category, &$category->params, 0));
		// Compile the event data
		$category->event->afterDisplayTitle = trim(implode("\n", $results));

		// Fetch the event data
		$results = $this->app->triggerEvent('onContentBeforeDisplay', array($category->extension . '.categories', &$category, &$category->params, 0));
		// Compile the event data
		$category->event->beforeDisplayContent = trim(implode("\n", $results));

		// Fetch the event data
		$results = $this->app->triggerEvent('onContentAfterDisplay', array($category->extension . '.categories', &$category, &$category->params, 0));
		// Compile the event data
		$category->event->afterDisplayContent = trim(implode("\n", $results));

		$category->description = $category->text;

		$this->category = $category;

		$model->setState('filter.context', $category->extension);

		$this->entities = $this->get('Items');

		foreach ($this->entities as $entity) {
			$entity->text = '';
			$this->app->triggerEvent('onContentPrepare', array($category->extension, &$entity, &$entity->params, 0));
		}

		$this->filterForm = $this->get('AdvancedFiltersForm');

		// Create the array with ids as key
		$fields = [];
		foreach (FieldsHelper::getFields($category->extension) as $field) {
			$fields[$field->id] = $field;
		}

		$this->fields = $fields;

		// Add a field for the category
		$categoryField           = new stdClass();
		$categoryField->id       = -1;
		$categoryField->name     = 'internal_category';
		$categoryField->title    = JText::_('JCATEGORY');
		$categoryField->label    = $categoryField->title;
		$categoryField->value    = $categoryField->title;
		$categoryField->rawvalue = -1;
		$categoryField->params   = new \Joomla\Registry\Registry(['showlabel' => 1]);

		$this->fields[-1] = $categoryField;
	}
}
