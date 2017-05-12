<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Basic\Link;
use CCL\Content\Element\Basic\Table;
use CCL\Content\Element\Component\Icon;
use DPFields\Helper\DPFieldsHelper;

// Will be removed on Joomla 3.7.2 when pr https://github.com/joomla/joomla-cms/pull/15826 got merged.
JHtml::register('grid.sort',
	function ($title, $order, $direction = 'asc', $selected = '', $task = null, $new_direction = 'asc', $tip = '', $form = null) {
		JHtml::_('behavior.core');
		JHtml::_('bootstrap.popover');

		$direction = strtolower($direction);
		$icon      = array('arrow-up-3', 'arrow-down-3');
		$index     = (int)($direction == 'desc');

		if ($order != $selected) {
			$direction = $new_direction;
		} else {
			$direction = ($direction == 'desc') ? 'asc' : 'desc';
		}

		if ($form) {
			$form = ', document.getElementById(\'' . $form . '\')';
		}

		$html = '<a href="#" onclick="Joomla.tableOrdering(\'' . $order . '\',\'' . $direction . '\',\'' . $task . '\'' . $form . ');return false;"'
			. ' class="hasPopover" title="' . htmlspecialchars(JText::_($tip ?: $title)) . '"'
			. ' data-content="' . htmlspecialchars(JText::_('JGLOBAL_CLICK_TO_SORT_THIS_COLUMN')) . '" data-placement="top">';

		if (isset($title['0']) && $title['0'] == '<') {
			$html .= $title;
		} else {
			$html .= JText::_($title);
		}

		if ($order == $selected) {
			$html .= '<span class="icon-' . $icon[$index] . '"></span>';
		}

		$html .= '</a>';

		return $html;
	}
);

// Ordering parameters
$listOrder = $this->escape($this->state->get('list.ordering', 'a.title'));
$listDirn  = $this->escape($this->state->get('list.direction'));

// Determine the return url
$return = $this->input->getInt('Itemid', null);
if (!empty($return)) {
	$return = 'index.php?Itemid=' . $return;
}

// The columns
$cols = array(
	JHtml::_('grid.sort', JText::_('JGLOBAL_TITLE'), 'a.title', $listDirn, $listOrder, null, 'asc', '', 'dp-category-filters-form')
);

// Loop over the configured columns
foreach ($this->params->get('category_columns') as $col) {
	// When there is only one column, make it sortable
	if (count($col['fields']) == 1) {
		$cols[] = JHtml::_('grid.sort', $col['name'], 'jcfield' . $col['fields'][0], $listDirn, $listOrder, null, 'asc', '',
			'dp-category-filters-form');
	} else {
		$cols[] = $col['name'];
	}
}

// Create the table
$table = new Table('fields', $cols);

// Loop over the entities
foreach ($this->entities as $entity) {
	// Create the row
	$row = $table->addRow(new Table\Row($entity->id));

	// The title cell
	$cell = $row->addCell(new Table\Cell('title'));

	if ($this->user->authorise('com_dpfields.entity.' . $entity->id, 'core.edit')) {
		$l = $cell->addChild(new Link('edit', DPFieldsHelperRoute::getEntityFormRoute($entity->id, $return)));
		$l->addChild(new Icon('book-icon', Icon::EDIT, array(), array('title' => JText::_('JACTION_EDIT'))));
	}

	// Add a cell with the title
	$link = new Link('link', JRoute::_(DPFieldsHelperRoute::getEntityRoute($entity->id, $entity->catid, $entity->language)));
	$link->setContent($entity->title);
	$cell->addChild($link);

	// Loop over the configured columns
	foreach ($this->params->get('category_columns') as $index => $col) {
		// The cell for the column
		$cell = $row->addCell(new Table\Cell($index));

		// Loop over the selected fields for that column
		foreach ($col['fields'] as $fieldId) {
			// Fill the content of the cell with the value of the field
			$field = $entity->jcfields[$fieldId];
			$cell->addChild(new Container($field->id))->setContent($field->value);
		}
	}
}

// Add the table to the root container
$this->root->addChild($table);

// The add button
$user = JFactory::getUser();
if ($user->authorise('core.create', 'com_dpfields') || count($user->getAuthorisedCategories($this->category->extension, 'core.create'))) {
	// Add the create button
	DPFieldsHelper::renderLayout(
		'content.button',
		array(
			'type'    => Icon::FILE,
			'root'    => $this->root,
			'text'    => 'JACTION_CREATE',
			'onclick' => "location.href='" . DPFieldsHelperRoute::getEntityFormRoute($this->category->extension, $return) . "'"
		)
	);
}