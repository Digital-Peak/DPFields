<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Container;

/**
 * Layout variables
 * -----------------
 * @var stdClass  $field
 * @var object    $entity
 * @var Container $root
 **/
extract($displayData);

$root->addChild(new Container('value'))->setContent($field->value);
