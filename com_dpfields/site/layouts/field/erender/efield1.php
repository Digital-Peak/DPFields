<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

	$field = $displayData['field'] ?: array();

	$label = $field->title;
	$value = $field->value;

	if (! $value)
	{
		return;
	}
		$class = '';
		if (isset($field->class))
		{
			$class = $field->class;
		}
		$id = (int) $field->id;
		?>

	<div class="dpfield-entry <?php echo $class;?>">
		<span class="dpfield-label"><?php echo htmlentities($label);?>: </span>
		<span class="dpfield-value"><?php echo $value;?></span>
	</div>
