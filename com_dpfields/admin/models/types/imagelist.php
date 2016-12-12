<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2016 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.base', JPATH_ADMINISTRATOR);

class DPFieldsTypeImagelist extends DPFieldsTypeBase
{

	protected function postProcessDomNode ($field, DOMElement $fieldNode, JForm $form)
	{
		$fieldNode->setAttribute('hide_default', 'true');
		$fieldNode->setAttribute('hide_none', 'true');

		$element = $fieldNode->appendChild(new DOMElement('option', JText::_('JOPTION_DO_NOT_USE')));
		$element->setAttribute('value', '');

		return parent::postProcessDomNode($field, $fieldNode, $form);
	}
}
