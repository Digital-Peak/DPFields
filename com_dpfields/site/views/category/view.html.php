<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
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
		$dispatcher = JEventDispatcher::getInstance();

		$category       = $this->getModel()->getCategory($this->input->getInt('id'));
		$category->text = $category->description;

		$category->event = new stdClass();

		$dispatcher->trigger('onContentPrepare', array($category->extension . '.categories', &$category, &$category->params, 0));

		// Fetch the event data
		$results = $dispatcher->trigger('onContentAfterTitle', array($category->extension . '.categories', &$category, &$category->params, 0));
		// Compile the event data
		$category->event->afterDisplayTitle = trim(implode("\n", $results));

		// Fetch the event data
		$results = $dispatcher->trigger('onContentBeforeDisplay', array($category->extension . '.categories', &$category, &$category->params, 0));
		// Compile the event data
		$category->event->beforeDisplayContent = trim(implode("\n", $results));

		// Fetch the event data
		$results = $dispatcher->trigger('onContentAfterDisplay', array($category->extension . '.categories', &$category, &$category->params, 0));
		// Compile the event data
		$category->event->afterDisplayContent = trim(implode("\n", $results));

		$category->description = $category->text;

		$this->category = $category;

		$model = $this->getModel();
		$model->setState('filter.context', $category->extension);

		$this->entities = $this->get('Items');

		foreach ($this->entities as $entity) {
			$entity->text = '';
			$dispatcher->trigger('onContentPrepare', array($category->extension, &$entity, &$entity->params, 0));
		}

		$this->filterForm = $this->get('AdvancedFiltersForm');
	}
}
