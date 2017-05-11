<?php

namespace CCL\Content\Element\Component;

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Component\Grid\Row;

/**
 * A grid representation.
 */
class Grid extends Container
{

	/**
	 * Adds the given row to the internal childs and returns it for chaining.
	 *
	 * @param Row $row
	 *
	 * @return Row
	 */
	public function addRow(Row $row)
	{
		return $this->addChild($row);
	}
}
