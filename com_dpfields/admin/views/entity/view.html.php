<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsViewEntity extends \DPFields\View\LayoutView
{
	protected $layoutName = 'entity.form.default';

	public function init()
	{
		$this->form       = $this->get('Form');
		$this->entity     = $this->get('Item');
		$this->returnPage = '';

		// If we are forcing a language in modal (used for associations).
		if ($this->getLayout() === 'modal' && $forcedLanguage = JFactory::getApplication()->input->get('forcedLanguage', '', 'cmd')) {
			// Set the language field to the forcedLanguage and disable changing it.
			$this->form->setValue('language', null, $forcedLanguage);
			$this->form->setFieldAttribute('language', 'readonly', 'true');

			// Only allow to select categories with All language or with the forced language.
			$this->form->setFieldAttribute('catid', 'language', '*,' . $forcedLanguage);

			// Only allow to select tags with All language or with the forced language.
			$this->form->setFieldAttribute('tags', 'language', '*,' . $forcedLanguage);
		}


		if (JLanguageAssociations::isEnabled()) {
			JHtml::_('jquery.framework');
			JHtml::_('script', 'com_dpfields/layouts/entity/form/default.js', false, true);
			JLayoutHelper::render('joomla.edit.associations', $this);
		}
	}

	protected function addToolbar()
	{
		$this->input->set('hidemainmenu', true);

		$user       = $this->user;
		$userId     = $user->id;
		$isNew      = ($this->entity->id == 0);
		$checkedOut = !($this->entity->checked_out == 0 || $this->entity->checked_out == $userId);

		// Built the actions for new and existing records.
		$canDo = JHelperContent::getActions('com_dpfields', 'entity', $this->entity->id);

		JToolbarHelper::title(
			JText::_('COM_DPFIELDS_VIEW_ENTITY_' . ($checkedOut ? 'VIEW' : ($isNew ? 'ADD' : 'EDIT'))),
			'pencil-2 entity-add'
		);

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_dpfields', 'core.create')) > 0)) {
			JToolbarHelper::apply('entity.apply');
			JToolbarHelper::save('entity.save');
			JToolbarHelper::save2new('entity.save2new');
			JToolbarHelper::cancel('entity.cancel');
		} else {
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$entityEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->entity->created_by == $userId);

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $entityEditable) {
				JToolbarHelper::apply('entity.apply');
				JToolbarHelper::save('entity.save');

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create')) {
					JToolbarHelper::save2new('entity.save2new');
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				JToolbarHelper::save2copy('entity.save2copy');
			}

			if (JComponentHelper::isEnabled('com_contenthistory') && $this->state->params->get('save_history', 0) && $entityEditable) {
				JToolbarHelper::versions('com_dpfields.entity', $this->entity->id);
			}

			JToolbarHelper::cancel('entity.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
