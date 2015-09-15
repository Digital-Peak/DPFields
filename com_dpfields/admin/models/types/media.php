<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.base', JPATH_ADMINISTRATOR);

class DPFieldsTypeMedia extends DPFieldsTypeBase
{

	public function prepareValueForDisplay ($value, $field)
	{
		$value = (array) $value;

		$buffer = '';
		foreach ($value as $path)
		{
			if (! $path)
			{
				continue;
			}
			$buffer .= '<img src="' . $path . '"/>';
		}
		return $buffer;
	}

	protected function postProcessDomNode ($field, DOMElement $fieldNode, JForm $form)
	{
		$fieldNode->setAttribute('hide_default', 'true');

		return parent::postProcessDomNode($field, $fieldNode, $form);
	}
}
