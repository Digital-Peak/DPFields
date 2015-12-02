<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

if (! key_exists('field', $displayData))
{
	return;
}

$field = $displayData['field'];
$value = $field->value;
if (! $value)
{
	return;
}

$value = (array) $value;

$texts = array();
foreach (DPFieldsHelper::loadTypeObject($field->type, $field->context)->getOptions($field) as $index => $optionsValue)
{
	if (in_array($index, $value))
	{
		$texts[] = $optionsValue;
	}
}

echo htmlentities(implode(', ', $texts));
