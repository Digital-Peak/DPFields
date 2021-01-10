<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
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
$c->addChild(new TextBlock('label', array('label')))->setContent(JText::_($field->label));
$c->addChild(new TextBlock('value', array('value')))->setContent($field->value);
