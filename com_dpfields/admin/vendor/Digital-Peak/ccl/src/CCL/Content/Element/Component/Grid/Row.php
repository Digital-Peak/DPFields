<?php

namespace CCL\Content\Element\Component\Grid;

use CCL\Content\Element\Basic\Container;

/**
 * A row representation.
 */
class Row extends Container
{
	/**
	 * Adds the given column to the internal childs and returns it for chaining.
	 *
	 * @param Column $column
	 *
	 * @return Column
	 */
	public function addColumn(Column $column)
	{
		return $this->addChild($column);
	}
}
