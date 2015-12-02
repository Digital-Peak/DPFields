<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

// Check if we have all data
if (! key_exists('item', $displayData) || ! key_exists('context', $displayData))
{
	return;
}

// Setting up for display
$item = $displayData['item'];
if (! $item)
{
	return;
}
$context = $displayData['context'];
if (! $context)
{
	return;
}

JLoader::register('DPFieldsHelper', JPATH_ADMINISTRATOR . '/components/com_dpfields/helpers/dpfields.php');

$parts = explode('.', $context);
$component = $parts[0];

$fields = DPFieldsHelper::getFields($context, $item, true);
if (! $fields)
{
	return;
}

// Load some output definitions
$container = 'dl';
if (key_exists('container', $displayData) && $displayData['container'])
{
	$container = $displayData['container'];
}
$class = 'article-info muted';
if (key_exists('container-class', $displayData) && $displayData['container-class'])
{
	$class = $displayData['container-class'];
}

// Print the container tag
echo '<' . $container . ' class="' . $class . '">';

// Loop trough the fields and print them
foreach ($fields as $field)
{
	// If the value is empty dp nothing
	if (! isset($field->value) || ! $field->value)
	{
		continue;
	}

	echo DPFieldsHelper::render($context, 'field.render',
			array(
					'field' => $field,

					// @deprecated use $field->label directly in the render
					// layout of the field
					'label' => $field->label,
					// @deprecated use $field->value directly in the render
					// layout of the field
					'value' => $field->value,
					// @deprecated use $field->render_class directly in the
					// render layout of the field
					'class' => $field->render_class
			));
}

// Close the container
echo '</' . $container . '>';
