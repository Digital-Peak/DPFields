<?php

namespace CCL\Content\Element\Basic\Table;

use CCL\Content\Element\Basic\Container;

/**
 * A table row representation.
 */
class Row extends Container
{

	/**
	 * Adds the given cell to the internal child collection.
	 *
	 * @param Cell $cell
	 *
	 * @return Cell
	 */
	public function addCell(Cell $cell)
	{
		return $this->addChild($cell);
	}
}
