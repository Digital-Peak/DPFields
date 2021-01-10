<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsViewContentTypes extends \DPFields\View\BaseView
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

	protected function init()
	{
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->authors = $this->get('Authors');
	}

	protected function addToolbar()
	{
		$canDo = JHelperContent::getActions('com_dpfields');
		$user = JFactory::getUser();

		JToolbarHelper::title(JText::_('COM_DPFIELDS_CONTENT_TYPES'), 'stack article');

		if ($canDo->get('core.create')) {
			JToolbarHelper::addNew('contenttype.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
			JToolbarHelper::editList('contenttype.edit');
		}

		if ($canDo->get('core.edit.state')) {
			JToolbarHelper::publish('contenttypes.publish', 'JTOOLBAR_PUBLISH', true);
			JToolbarHelper::unpublish('contenttypes.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolbarHelper::archiveList('contenttypes.archive');
			JToolbarHelper::checkin('contenttypes.checkin');
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'contenttypes.delete', 'JTOOLBAR_EMPTY_TRASH');
		} elseif ($canDo->get('core.edit.state')) {
			JToolbarHelper::trash('contenttypes.trash');
		}

		if ($user->authorise('core.admin', 'com_dpfields') || $user->authorise('core.options', 'com_dpfields')) {
			JToolbarHelper::preferences('com_dpfields');
		}

		return parent::addToolbar();
	}

	protected function getSortFields()
	{
		return array(
			'a.ordering'   => JText::_('JGRID_HEADING_ORDERING'),
			'a.state'      => JText::_('JSTATUS'),
			'a.title'      => JText::_('JGLOBAL_TITLE'),
			'a.created_by' => JText::_('JAUTHOR'),
			'a.created'    => JText::_('JDATE'),
			'a.id'         => JText::_('JGRID_HEADING_ID')
		);
	}
}
