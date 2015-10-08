<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

if (! key_exists('label', $displayData) || ! key_exists('value', $displayData))
{
	return;
}

$label = $displayData['label'];
$value = $displayData['value'];
if (! $value)
{
	return;
}

$class = '';
if (isset($displayData['class']))
{
	$class = $displayData['class'];
}
?>

<dd class="dpfield-entry <?php echo $class;?>">
	<span class="dpfield-label"><?php echo htmlentities($label);?>: </span>
	<span class="dpfield-value"><?php echo $value;?></span>
</dd>
