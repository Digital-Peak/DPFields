<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.base', JPATH_ADMINISTRATOR);

class DPFieldsTypeSql extends DPFieldsTypeBase
{

	public function prepareValueForDisplay ($value, $field)
	{
		$db = JFactory::getDbo();
		$value = (array) $value;

		$condition = '';
		foreach ($value as $v)
		{
			if (! $v)
			{
				continue;
			}
			$condition .= ', ' . $db->q($v);
		}

		$query = $field->fieldparams->get('query', 'select id as value, name as text from #__users');

		// Run the query with a having condition because it support aliases
		$db->setQuery($query . ' having value in (' . trim($condition, ',') . ')');

		$items = array();
		try
		{
			$items = $db->loadObjectlist();
		}
		catch (Exception $e)
		{
			// If the query failed, we fetch all elements
			$db->setQuery($query);
			$items = $db->loadObjectlist();
		}

		$texts = array();
		foreach ($items as $item)
		{
			if (in_array($item->value, $value))
			{
				$texts[] = $item->text;
			}
		}
		return parent::prepareValueForDisplay($texts, $field);
	}

	protected function postProcessDomNode ($field, DOMElement $fieldNode, JForm $form)
	{
		$fieldNode->setAttribute('value_field', 'text');
		$fieldNode->setAttribute('key_field', 'value');

		if (! $fieldNode->getAttribute('query'))
		{
			$fieldNode->setAttribute('query', 'select id as value, name as text from #__users');
		}

		return parent::postProcessDomNode($field, $fieldNode, $form);
	}
}
