<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.base', JPATH_ADMINISTRATOR);

class DPFieldsTypeUrl extends DPFieldsTypeBase
{

	public function prepareValueForDisplay ($value, $field)
	{
		if (! $value)
		{
			return $value;
		}

		$attributes = '';
		if (! JUri::isInternal($value))
		{
			$attributes = 'rel="nofollow" target="_blank"';
		}
		return '<a href="' . $value . '" ' . $attributes . '>' . $value . '</a>';
	}

	protected function postProcessDomNode ($field, DOMElement $fieldNode, JForm $form)
	{
		$fieldNode->setAttribute('validate', 'url');
		if (! $fieldNode->getAttribute('relative'))
		{
			$fieldNode->removeAttribute('relative');
		}

		return parent::postProcessDomNode($field, $fieldNode, $form);
	}
}
