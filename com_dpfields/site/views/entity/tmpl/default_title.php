<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Basic\DescriptionListHorizontal;
use CCL\Content\Element\Basic\Description\Description;
use CCL\Content\Element\Basic\Description\Term;
use CCL\Content\Element\Basic\Heading;
use CCL\Content\Element\Basic\Link;

// The category
$entity = $this->entity;

// The category container
$c = $this->root->addChild(new Container($entity->id));

// The category of the entity
$category = JCategories::getInstance('DPFields', array('extension' => 'com_dpfields.' . $this->contentType->name))->get($entity->catid);

// The title
$c->addChild(new Heading('title', 2))->setContent($entity->title);

$dl = $this->root->addChild(new DescriptionListHorizontal('category'));
$dl->setTerm(new Term('label'))->setContent(JText::_('JCATEGORY'));

// The category link
$l = $dl->setDescription(new Description('value'))->addChild(new Link('category', DPFieldsHelperRoute::getCategoryRoute($category->id)));
$l->setContent($category->title);

// The tags when available
if ($entity->tags->itemTags) {
	$c->addChild(new Container('tags'))->setContent(JLayoutHelper::render('joomla.content.tags', $entity->tags->itemTags));
}
