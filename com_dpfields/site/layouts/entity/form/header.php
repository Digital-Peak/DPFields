<?php
/**
 * @package    DPCalendar
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2007 - 2016 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Component\Icon;
use CCL\Content\Element\Basic\TextBlock;
use DPFields\Helper\DPFieldsHelper;

/**
 * Layout variables
 * -----------------
 * @var Container $root
 * @var object    $entity
 * @var object    $form
 * @var object    $params
 * @var string    $returnPage
 **/
extract($displayData);

/** @var Container $root * */
$root = $root->addChild(new Container('actions'));

// Create the save button
DPFieldsHelper::renderLayout(
	'content.button',
	array(
		'id'      => 'apply',
		'type'    => Icon::OK,
		'root'    => $root,
		'text'    => 'COM_DPFIELDS_SAVE',
		'onclick' => "Joomla.submitbutton('entityform.apply')"
	)
);


// Create the save and close button
DPFieldsHelper::renderLayout(
	'content.button',
	array(
		'id'      => 'save',
		'type'    => Icon::OK,
		'root'    => $root,
		'text'    => 'COM_DPFIELDS_SAVE_AND_CLOSE',
		'onclick' => "Joomla.submitbutton('entityform.save')"
	)
);

// Create the save and new button
DPFieldsHelper::renderLayout(
	'content.button',
	array(
		'id'      => 'save2new',
		'type'    => Icon::OK,
		'root'    => $root,
		'text'    => 'COM_DPFIELDS_SAVE_AND_NEW',
		'onclick' => "Joomla.submitbutton('entityform.save2new')"
	)
);

// Create the save as copy button
DPFieldsHelper::renderLayout(
	'content.button',
	array(
		'id'      => 'save2copy',
		'type'    => Icon::OK,
		'root'    => $root,
		'text'    => 'COM_DPFIELDS_SAVE_AS_COPY',
		'onclick' => "Joomla.submitbutton('entityform.save2copy')"
	)
);

// Create the cancel button
DPFieldsHelper::renderLayout(
	'content.button',
	array(
		'type'    => Icon::CANCEL,
		'root'    => $root,
		'text'    => 'JCANCEL',
		'onclick' => "Joomla.submitbutton('entityform.cancel')"
	)
);

if ($entity->id && false) {
	// Create the delete button
	DPFieldsHelper::renderLayout(
		'content.button',
		array(
			'type'    => Icon::DELETE,
			'root'    => $root,
			'text'    => 'JACTION_DELETE',
			'onclick' => "Joomla.submitbutton('entityform.delete')"
		)
	);
}
