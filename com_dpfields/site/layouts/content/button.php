<?php
/**
 * @package    DPCalendar
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2007 - 2016 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Button;
use CCL\Content\Element\Component\Icon;

// Global required variables
$root    = $displayData['root'];
$type    = $displayData['type'];
$onclick = $displayData['onclick'];

// Global optional variables
$id    = isset($displayData['id']) ? $displayData['id'] : $type;
$text  = isset($displayData['text']) ? $displayData['text'] : '';
$title = isset($displayData['title']) ? $displayData['title'] : $text;

// The icon of the button
$icon = new Icon('icon', $type);

// Create the button
$button = new Button(
	$id,
	html_entity_decode(JText::_($text)),
	$icon,
	array('hasTooltip', $id),
	array(
		'title'   => html_entity_decode(JText::_($title)),
		'onclick' => $onclick
	)
);
$button->setProtectedClass('hasTooltip');

// Add it to the parent
$root->addChild($button);
