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
use CCL\Content\Element\Basic\Heading;
use CCL\Content\Element\Basic\Link;

// The category
$entity = $this->entity;

// The category container
$c = $this->root->addChild(new Container($entity->id));

// The title
$c->addChild(new Heading('title', 2))->setContent($entity->title);

// The tags when available
if ($entity->tags->itemTags) {
	$c->addChild(new Container('tags'))->setContent(JLayoutHelper::render('joomla.content.tags', $entity->tags->itemTags));
}
