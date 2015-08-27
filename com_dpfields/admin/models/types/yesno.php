<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.list', JPATH_ADMINISTRATOR);

class DPFieldsTypeYesno extends DPFieldsTypeList
{

	protected function postProcessDomNode ($field, DOMElement $fieldNode, JForm $form)
	{
		$fieldNode->setAttribute('type', 'radio');
		if (strpos($field->class, 'btn-group-yesno') === false)
		{
			$fieldNode->setAttribute('class', $field->class . ' btn-group-yesno');
		}

		return parent::postProcessDomNode($field, $fieldNode, $form);
	}

	protected function getOptions ($field)
	{
		return array(
				1 => JText::_('JYES'),
				0 => JText::_('JNO')
		);
	}
}
