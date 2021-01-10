<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsViewContentType extends \DPFields\View\LayoutView
{
	protected $layoutName = 'contenttype.form.default';

	public function init()
	{
		$this->form        = $this->get('Form');
		$this->contentType = $this->get('Item');
	}

	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user       = JFactory::getUser();
		$userId     = $user->id;
		$isNew      = ($this->contentType->id == 0);
		$checkedOut = !($this->contentType->checked_out == 0 || $this->contentType->checked_out == $userId);

		// Built the actions for new and existing records.
		$canDo = JHelperContent::getActions('com_dpfields', 'contenttype', $this->contentType->id);

		JToolbarHelper::title(
			JText::_('COM_DPFIELDS_VIEW_CONTENT_TYPE_' . ($checkedOut ? 'VIEW' : ($isNew ? 'ADD' : 'EDIT'))),
			'pencil-2 article-add'
		);

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_dpfields', 'core.create')) > 0)) {
			JToolbarHelper::apply('contenttype.apply');
			JToolbarHelper::save('contenttype.save');
			JToolbarHelper::save2new('contenttype.save2new');
			JToolbarHelper::cancel('contenttype.cancel');
		} else {
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$contentTypeEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->contentType->created_by == $userId);

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $contentTypeEditable) {
				JToolbarHelper::apply('contenttype.apply');
				JToolbarHelper::save('contenttype.save');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create')) {
					JToolbarHelper::save2new('contenttype.save2new');
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				JToolbarHelper::save2copy('contenttype.save2copy');
			}

			JToolbarHelper::cancel('contenttype.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
