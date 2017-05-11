<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use DPFields\Helper\DPFieldsHelper;

class DPFieldsViewEntities extends \DPFields\View\BaseView
{
	/**
	 * The item authors
	 *
	 * @var  stdClass
	 */
	protected $authors;

	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;

	/**
	 * The pagination object
	 *
	 * @var  JPagination
	 */
	protected $pagination;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var  JForm
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  array
	 */
	public $activeFilters;

	/**
	 * The sidebar markup
	 *
	 * @var  string
	 */
	protected $sidebar;

	public function init()
	{
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->authors       = $this->get('Authors');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->vote          = JPluginHelper::isEnabled('content', 'vote');

		// Levels filter - Used in Hathor.
		$this->f_levels = array(
			JHtml::_('select.option', '1', JText::_('J1')),
			JHtml::_('select.option', '2', JText::_('J2')),
			JHtml::_('select.option', '3', JText::_('J3')),
			JHtml::_('select.option', '4', JText::_('J4')),
			JHtml::_('select.option', '5', JText::_('J5')),
			JHtml::_('select.option', '6', JText::_('J6')),
			JHtml::_('select.option', '7', JText::_('J7')),
			JHtml::_('select.option', '8', JText::_('J8')),
			JHtml::_('select.option', '9', JText::_('J9')),
			JHtml::_('select.option', '10', JText::_('J10')),
		);

		// We don't need toolbar in the modal window.
		if ($this->getLayout() == 'modal')
		{
			// In article associations modal we need to remove language filter if forcing a language.
			// We also need to change the category filter to show show categories with All or the forced language.
			if ($forcedLanguage = JFactory::getApplication()->input->get('forcedLanguage', '', 'CMD'))
			{
				// If the language is forced we can't allow to select the language, so transform the language selector filter into an hidden field.
				$languageXml = new SimpleXMLElement('<field name="language" type="hidden" default="' . $forcedLanguage . '" />');
				$this->filterForm->setField($languageXml, 'filter', true);

				// Also, unset the active language filter so the search tools is not open by default with this filter.
				unset($this->activeFilters['language']);

				// One last changes needed is to change the category filter to just show categories with All language or with the forced language.
				$this->filterForm->setFieldAttribute('category_id', 'language', '*,' . $forcedLanguage, 'filter');
			}
		}

		return parent::init();
	}

	protected function addToolbar()
	{
		$canDo = JHelperContent::getActions('com_dpfields', 'category', $this->state->get('filter.category_id'));
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolbar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_DPFIELDS_ENTITIES'), 'stack article');

		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_dpfields', 'core.create'))) > 0)
		{
			JToolbarHelper::addNew('entity.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own')))
		{
			JToolbarHelper::editList('entity.edit');
		}

		if ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::publish('entities.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('entities.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::custom('entities.featured', 'featured.png', 'featured_f2.png', 'JFEATURE', true);
			JToolbarHelper::custom('entities.unfeatured', 'unfeatured.png', 'featured_f2.png', 'JUNFEATURE', true);
			JToolbarHelper::archiveList('entities.archive');
			JToolbarHelper::checkin('entities.checkin');
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_dpfields')
			&& $user->authorise('core.edit', 'com_dpfields')
			&& $user->authorise('core.edit.state', 'com_dpfields'))
		{
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'entities.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		elseif ($canDo->get('core.edit.state'))
		{
			JToolbarHelper::trash('entities.trash');
		}

		if ($user->authorise('core.admin', 'com_dpfields') || $user->authorise('core.options', 'com_dpfields'))
		{
			JToolbarHelper::preferences('com_dpfields');
		}
	}

	protected function getSortFields()
	{
		return array(
			'a.ordering'     => JText::_('JGRID_HEADING_ORDERING'),
			'a.state'        => JText::_('JSTATUS'),
			'a.title'        => JText::_('JGLOBAL_TITLE'),
			'category_title' => JText::_('JCATEGORY'),
			'access_level'   => JText::_('JGRID_HEADING_ACCESS'),
			'a.created_by'   => JText::_('JAUTHOR'),
			'language'       => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.created'      => JText::_('JDATE'),
			'a.id'           => JText::_('JGRID_HEADING_ID'),
			'a.featured'     => JText::_('JFEATURED')
		);
	}
}
