<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Basic\Link;
use CCL\Content\Element\Basic\Table;
use CCL\Content\Element\Component\Icon;
use DPFields\Helper\DPFieldsHelper;

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
	if (!key_exists('fields', $col)) {
		continue;
	}

	$fieldsToDisplay = $col['fields'];

	// Check which fields do exist
	foreach ($fieldsToDisplay as $key => $fieldId) {
		if (!key_exists($fieldId, $this->fields)) {
			unset($fieldsToDisplay[$key]);
		}
	}

	if (!$fieldsToDisplay) {
		continue;
	}

	// When there is only one column, make it sortable
	if (count($fieldsToDisplay) == 1) {
		$cols[] = JHtml::_('grid.sort', $col['name'], 'jcfield' . $fieldsToDisplay[0], $listDirn, $listOrder, null, 'asc', '',
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
		$cell = null;

		// Loop over the selected fields for that column
		foreach ($col['fields'] as $fieldId) {
			if (!key_exists($fieldId, $this->fields)) {
				continue;
			}

			if (!key_exists($fieldId, $entity->jcfields) && $fieldId != -1) {
				continue;
			}

			if ($cell == null) {
				$cell = $row->addCell(new Table\Cell($index));
			}

			if ($fieldId == -1) {
				$cell->addChild(new Container(-1))->setContent($entity->category_title);
				continue;
			}

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
			'type' => Icon::FILE,
			'root' => $this->root,
			'text' => 'JACTION_CREATE',
			'onclick' => "location.href='" . DPFieldsHelperRoute::getEntityFormRoute($this->category->extension, $return) . "'"
		)
	);
}