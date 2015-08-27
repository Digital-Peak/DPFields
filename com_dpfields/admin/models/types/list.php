<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.base', JPATH_ADMINISTRATOR);

class DPFieldsTypeList extends DPFieldsTypeBase
{

	public function prepareValueForDisplay ($value, $field)
	{
		if (! is_array($value))
		{
			$value = array(
					$value
			);
		}

		$texts = array();
		foreach ($this->getOptions($field) as $index => $optionsValue)
		{
			if (in_array($index, $value))
			{
				$texts[] = $optionsValue;
			}
		}
		return parent::prepareValueForDisplay($texts, $field);
	}

	/**
	 * Returns an array of key values to put in a list.
	 *
	 * @param stdClass $field
	 * @return array
	 */
	protected function getOptions ($field)
	{
		$options = $field->fieldparams->get('options', array());
		if (! is_array($options))
		{
			$options = json_decode($options);
		}
		$data = array();
		if (isset($options->key))
		{
			foreach ($options->key as $index => $key)
			{
				$data[$key] = $options->value[$index];
			}
		}
		return $data;
	}

	protected function postProcessDomNode ($field, DOMElement $fieldNode, JForm $form)
	{
		foreach ($this->getOptions($field) as $index => $value)
		{
			$element = $fieldNode->appendChild(new DOMElement('option', $value));
			$element->setAttribute('value', $index);
		}

		return parent::postProcessDomNode($field, $fieldNode, $form);
	}
}
