<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('JPATH_BASE') or die;

/**
 * Supports a modal article picker.
 *
 * @since  1.6
 */
class JFormFieldModal_Entity extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $type = 'ModalEntity';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
		$allowNew    = ((string)$this->element['new'] == 'true');
		$allowEdit   = ((string)$this->element['edit'] == 'true');
		$allowClear  = ((string)$this->element['clear'] != 'false');
		$allowSelect = ((string)$this->element['select'] != 'false');

		// Load language
		JFactory::getLanguage()->load('com_dpfields', JPATH_ADMINISTRATOR);

		// The active article id field.
		$value = (int)$this->value > 0 ? (int)$this->value : '';

		// Create the modal id.
		$modalId = 'Entity_' . $this->id;

		// Add the modal field script to the document head.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/modal-fields.js', array('version' => 'auto', 'relative' => true));

		// Script to proxy the select modal function to the modal-fields.js file.
		if ($allowSelect) {
			static $scriptSelect = null;

			if (is_null($scriptSelect)) {
				$scriptSelect = array();
			}

			if (!isset($scriptSelect[$this->id])) {
				JFactory::getDocument()->addScriptDeclaration("
				function jSelectEntity_" . $this->id . "(id, title, catid, object, url, language) {
					window.processModalSelect('Entity', '" . $this->id . "', id, title, catid, object, url, language);
				}
				");

				$scriptSelect[$this->id] = true;
			}
		}

		// Setup variables for display.
		$linkEntities = 'index.php?option=com_dpfields&amp;view=entities&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';
		$linkEntity   = 'index.php?option=com_dpfields&amp;view=entity&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1';

		$linkEntities .= '&context=' . $this->element['context'];
		$linkEntity   .= '&context=' . $this->element['context'];

		if (isset($this->element['language'])) {
			$linkEntities .= '&amp;forcedLanguage=' . $this->element['language'];
			$linkEntity   .= '&amp;forcedLanguage=' . $this->element['language'];
			$modalTitle   = JText::_('COM_DPFIELDS_CHANGE_ENTITY') . ' &#8212; ' . $this->element['label'];
		} else {
			$modalTitle = JText::_('COM_DPFIELDS_CHANGE_ENTITY');
		}

		$urlSelect = $linkEntities . '&amp;function=jSelectEntity_' . $this->id;
		$urlEdit   = $linkEntity . '&amp;task=entity.edit&amp;id=\' + document.getElementById("' . $this->id . '_id").value + \'';
		$urlNew    = $linkEntity . '&amp;task=entity.add';

		if ($value) {
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('title'))
				->from($db->quoteName('#__dpfields_entities'))
				->where($db->quoteName('id') . ' = ' . (int)$value);
			$db->setQuery($query);

			try {
				$title = $db->loadResult();
			} catch (RuntimeException $e) {
				JError::raiseWarning(500, $e->getMessage());
			}
		}

		$title = empty($title) ? JText::_('COM_DPFIELDS_SELECT_AN_ENTITY') : htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The current entity display field.
		$html = '<span class="input-append">';
		$html .= '<input class="input-medium" id="' . $this->id . '_name" type="text" value="' . $title . '" disabled="disabled" size="35" />';

		// Select entity button
		if ($allowSelect) {
			$html .= '<a'
				. ' class="btn hasTooltip' . ($value ? ' hidden' : '') . '"'
				. ' id="' . $this->id . '_select"'
				. ' data-toggle="modal"'
				. ' role="button"'
				. ' href="#ModalSelect' . $modalId . '"'
				. ' title="' . JHtml::tooltipText('COM_DPFIELDS_CHANGE_ENTITY') . '">'
				. '<span class="icon-file" aria-hidden="true"></span> ' . JText::_('JSELECT')
				. '</a>';
		}

		// New entity button
		if ($allowNew) {
			$html .= '<a'
				. ' class="btn hasTooltip' . ($value ? ' hidden' : '') . '"'
				. ' id="' . $this->id . '_new"'
				. ' data-toggle="modal"'
				. ' role="button"'
				. ' href="#ModalNew' . $modalId . '"'
				. ' title="' . JHtml::tooltipText('COM_DPFIELDS_NEW_ENTITY') . '">'
				. '<span class="icon-new" aria-hidden="true"></span> ' . JText::_('JACTION_CREATE')
				. '</a>';
		}

		// Edit entity button
		if ($allowEdit) {
			$html .= '<a'
				. ' class="btn hasTooltip' . ($value ? '' : ' hidden') . '"'
				. ' id="' . $this->id . '_edit"'
				. ' data-toggle="modal"'
				. ' role="button"'
				. ' href="#ModalEdit' . $modalId . '"'
				. ' title="' . JHtml::tooltipText('COM_DPFIELDS_EDIT_ENTITY') . '">'
				. '<span class="icon-edit" aria-hidden="true"></span> ' . JText::_('JACTION_EDIT')
				. '</a>';
		}

		// Clear entity button
		if ($allowClear) {
			$html .= '<a'
				. ' class="btn' . ($value ? '' : ' hidden') . '"'
				. ' id="' . $this->id . '_clear"'
				. ' href="#"'
				. ' onclick="window.processModalParent(\'' . $this->id . '\'); return false;">'
				. '<span class="icon-remove" aria-hidden="true"></span>' . JText::_('JCLEAR')
				. '</a>';
		}

		$html .= '</span>';

		// Select entity modal
		if ($allowSelect) {
			$html .= JHtml::_(
				'bootstrap.renderModal',
				'ModalSelect' . $modalId,
				array(
					'title'      => $modalTitle,
					'url'        => $urlSelect,
					'height'     => '400px',
					'width'      => '800px',
					'bodyHeight' => '70',
					'modalWidth' => '80',
					'footer'     => '<a role="button" class="btn" data-dismiss="modal" aria-hidden="true">' . JText::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>',
				)
			);
		}

		// New entity modal
		if ($allowNew) {
			$html .= JHtml::_(
				'bootstrap.renderModal',
				'ModalNew' . $modalId,
				array(
					'title'       => JText::_('COM_DPFIELDS_NEW_ENTITY'),
					'backdrop'    => 'static',
					'keyboard'    => false,
					'closeButton' => false,
					'url'         => $urlNew,
					'height'      => '400px',
					'width'       => '800px',
					'bodyHeight'  => '70',
					'modalWidth'  => '80',
					'footer'      => '<a role="button" class="btn" aria-hidden="true"'
						. ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'add\', \'entity\', \'cancel\', \'item-form\'); return false;">'
						. JText::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>'
						. '<a role="button" class="btn btn-primary" aria-hidden="true"'
						. ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'add\', \'entity\', \'save\', \'item-form\'); return false;">'
						. JText::_('JSAVE') . '</a>'
						. '<a role="button" class="btn btn-success" aria-hidden="true"'
						. ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'add\', \'entity\', \'apply\', \'item-form\'); return false;">'
						. JText::_('JAPPLY') . '</a>',
				)
			);
		}

		// Edit entity modal
		if ($allowEdit) {
			$html .= JHtml::_(
				'bootstrap.renderModal',
				'ModalEdit' . $modalId,
				array(
					'title'       => JText::_('COM_DPFIELDS_EDIT_ENTITY'),
					'backdrop'    => 'static',
					'keyboard'    => false,
					'closeButton' => false,
					'url'         => $urlEdit,
					'height'      => '400px',
					'width'       => '800px',
					'bodyHeight'  => '70',
					'modalWidth'  => '80',
					'footer'      => '<a role="button" class="btn" aria-hidden="true"'
						. ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'edit\', \'entity\', \'cancel\', \'item-form\'); return false;">'
						. JText::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</a>'
						. '<a role="button" class="btn btn-primary" aria-hidden="true"'
						. ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'edit\', \'entity\', \'save\', \'item-form\'); return false;">'
						. JText::_('JSAVE') . '</a>'
						. '<a role="button" class="btn btn-success" aria-hidden="true"'
						. ' onclick="window.processModalEdit(this, \'' . $this->id . '\', \'edit\', \'entity\', \'apply\', \'item-form\'); return false;">'
						. JText::_('JAPPLY') . '</a>',
				)
			);
		}

		// Note: class='required' for client side validation.
		$class = $this->required ? ' class="required modal-value"' : '';

		$html .= '<input type="hidden" id="' . $this->id . '_id" ' . $class . ' data-required="' . (int)$this->required . '" name="' . $this->name
			. '" data-text="' . htmlspecialchars(JText::_('COM_DPFIELDS_SELECT_AN_ENTITY', true), ENT_COMPAT,
				'UTF-8') . '" value="' . $value . '" />';

		return $html;
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   3.4
	 */
	protected function getLabel()
	{
		return str_replace($this->id, $this->id . '_id', parent::getLabel());
	}
}
