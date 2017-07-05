<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR);

class PlgFieldsDPFGallery extends FieldsPlugin
{
	public function onCustomFieldsPrepareDom($field, DOMElement $parent, JForm $form)
	{
		$fieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form);

		if (!$fieldNode) {
			return $fieldNode;
		}
		$directory = $fieldNode->getAttribute('directory');

		// All directories is selected
		if ($directory == '0') {
			$directory = '';
		}

		$fieldNode->setAttribute('directory', 'images/' . $directory);
		$fieldNode->setAttribute('hide_default', true);
		$fieldNode->setAttribute('hide_none', true);

		// Add all option
		$option            = new DOMElement('option');
		$option->nodeValue = htmlspecialchars('- ' . JText::_('JALL') . ' -', ENT_COMPAT, 'UTF-8');
		$fieldNode->appendChild($option);

		// Add none option
		$option            = new DOMElement('option', '-1');
		$option->nodeValue = htmlspecialchars('- ' . JText::_('JNONE') . ' -', ENT_COMPAT, 'UTF-8');
		$fieldNode->appendChild($option)->setAttribute('value', -1);

		return $fieldNode;
	}
}
