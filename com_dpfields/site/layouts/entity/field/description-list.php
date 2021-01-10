<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Basic\DescriptionListHorizontal;
use CCL\Content\Element\Basic\Description\Description;
use CCL\Content\Element\Basic\Description\Term;

/**
 * Layout variables
 * -----------------
 * @var stdClass  $field
 * @var object    $entity
 * @var Container $root
 **/
extract($displayData);

$dl = $root->addChild(new DescriptionListHorizontal('field', array('description')));
$dl->setTerm(new Term('label', array('label')))->setContent(JText::_($field->label));
$dl->setDescription(new Description('value', array('value')))->setContent($field->value);
