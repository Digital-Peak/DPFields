<?php

namespace CCL\Content\Element\Basic;

use CCL\Content\Element\Basic\Table\Row;
use CCL\Content\Element\Basic\Table\Cell;
use CCL\Content\Element\Basic\Table\Head;
use CCL\Content\Element\Basic\Table\Body;
use CCL\Content\Element\Basic\Table\Footer;
use CCL\Content\Element\Basic\Table\HeadCell;

/**
 * A Table representation.
 */
class Table extends Container
{

	private $head = null;

	private $body = null;

	private $footer = null;

	public function __construct($id, array $columns, array $classes = [], array $attributes = [])
	{
		parent::__construct($id, $classes, $attributes);

		$this->head = $this->addChild(new Head('head'));
		$this->body = $this->addChild(new Body('body'));
		$this->footer = $this->addChild(new Footer('footer'));

		$row = $this->head->addChild(new Row('row'));

		foreach ($columns as $index => $column) {
			$row->addChild(new HeadCell('cell-' . $index))->setContent($column);
		}
	}

	public function addRow(Row $row)
	{
		return $this->body->addChild($row);
	}

	public function addFooterRow(Row $row)
	{
		return $this->footer->addChild($row);
	}
}
