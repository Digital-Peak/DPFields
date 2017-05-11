<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Component\Icon;

/** @var Container $root * */
$root = $this->root->addChild(new Container('actions-container'));

$entity = $this->entity;
$return = DPFieldsHelperRoute::getEntityRoute($entity->id, $entity->catid, $entity->language);

// Compile the url fo the email button
require_once JPATH_SITE . '/components/com_mailto/helpers/mailto.php';
$uri = JUri::getInstance()->toString(array('scheme', 'host', 'port'));
$url = 'index.php?option=com_mailto&link=' . MailToHelper::addLink($uri . $return);

// Create the email button
DPFieldsHelper::renderLayout(
	'content.button',
	array(
		'type'    => Icon::MAIL,
		'root'    => $root,
		'text'   => 'JGLOBAL_EMAIL',
		'onclick' => "window.open('" . $url . "')"
	)
);

if ($this->user->authorise('com_dpfields.entity.' . $entity->id, 'core.edit')) {
	// Add the tickets button
	DPFieldsHelper::renderLayout(
		'content.button',
		array(
			'type'    => Icon::EDIT,
			'root'    => $root,
			'text'    => 'JACTION_EDIT',
			'onclick' => "location.href='" . DPFieldsHelperRoute::getEntityFormRoute($entity->id, $return) . "'"
		)
	);
}
