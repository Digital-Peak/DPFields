<?php
/**
 * @package    DPCalendar
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2007 - 2016 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Basic\TextBlock;

/**
 * Layout variables
 * -----------------
 * @var stdClass  $field
 * @var object    $entity
 * @var Container $root
 **/
extract($displayData);

$c = $root->addChild(new Container('field'));
$c->addChild(new TextBlock('label', array('label')))->setContent($field->label);
$c->addChild(new TextBlock('value', array('value')))->setContent($field->value);
