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
use CCL\Content\Element\Component\Grid;
use CCL\Content\Element\Component\Grid\Column;
use CCL\Content\Element\Component\Grid\Row;
use DPFields\Helper\DPFieldsHelper;

// The fields of the entity
$fields = $this->entity->jcfields;

// Add a field for the category
$categoryField           = new stdClass();
$categoryField->id       = -1;
$categoryField->name     = 'internal_category';
$categoryField->title    = JText::_('JCATEGORY');
$categoryField->label    = $categoryField->title;
$categoryField->value    = '<a href="' . DPFieldsHelperRoute::getCategoryRoute($this->category->id) . '">' . $this->category->title . '</a>';
$categoryField->rawvalue = $this->category->id;
$categoryField->params   = new \Joomla\Registry\Registry(['showlabel' => 1]);

$fields[-1] = $categoryField;

// Loop over the configured columns
foreach ($this->params->get('entity_sections') as $name => $section) {
	$s = $this->root->addChild(new Container($name));

	if ($section['name']) {
		$s->addChild(new Heading('heading', 3))->setContent($section['name']);
	}

	$grid = $s->addChild(new Grid('fields'));

	// Loop trough the fields
	$counter = 0;
	$row     = null;
	foreach ($section['fields'] as $fieldId) {
		if (!key_exists($fieldId, $fields)) {
			continue;
		}

		$columns = $section['columns'] ?: 1;
		if ($counter % $columns == 0) {
			$row = $grid->addRow(new Row(count($grid->getChildren()) + 1));
		}

		// Render the field
		$col = $row->addColumn(new Column($fieldId, 100 / $columns));

		$field = $fields[$fieldId];

		if (!$section['layout']) {
			$col->setContent(FieldsHelper::render('com_dpfields.' . $this->contentType->name, 'field.render', array('field' => $field)));
		} else {
			DPFieldsHelper::renderLayout(
				'entity.field.' . $section['layout'],
				array('field' => $field, 'entity' => $this->entity, 'root' => $col)
			);
		}
		$counter++;
	}
}
