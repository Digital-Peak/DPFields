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
foreach (DPFieldsHelper::getFields($context, $item, true) as $field)
{
	// If the value is empty dp nothing
	if (! isset($field->value) || ! $field->value)
	{
		continue;
	}

	$output = JLayoutHelper::render('field.render', array(
			'label' => $field->label,
			'value' => $field->value
	), null, array(
			'component' => $component,
			'client' => 0
	));

	/*
	 * Because the layout refreshes the paths before the render function is
	 * called, so there is no way to load the layout overrides in the order
	 * template -> context -> dpfields.
	 * If there is no override in the context then we need to call the layout
	 * from DPField.
	 */
	if (! $output)
	{
		$output = JLayoutHelper::render('field.render', array(
				'label' => $field->label,
				'value' => $field->value
		), null, array(
				'component' => 'com_dpfields',
				'client' => 0
		));
	}

	echo $output;
}

// Close the container
echo '</' . $container . '>';
