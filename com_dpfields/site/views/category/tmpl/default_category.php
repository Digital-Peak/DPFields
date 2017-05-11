<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Basic\Heading;
use CCL\Content\Element\Basic\Paragraph;

// The category
$category = $this->category;

// The category container
$c = $this->root->addChild(new Container($category->id));

// The title
$c->addChild(new Heading('title', 2))->setContent($category->title);

// The after title event content
$c->addChild(new Container('event-after-title'))->setContent($category->event->afterDisplayTitle);

// The before description event content
$c->addChild(new Container('event-before-description'))->setContent($category->event->beforeDisplayContent);

// The description
$c->addChild(new Container('description'))->setContent(JHtml::_('content.prepare', $category->description));

// The after description event content
$c->addChild(new Container('event-after-description'))->setContent($category->event->afterDisplayContent);
