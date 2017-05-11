<?php

namespace CCL\Content\Element\Component\Grid;

use CCL\Content\Element\Basic\Container;

/**
 * A column representation.
 */
class Column extends Container
{

	/**
	 * The width of the column as percentag.
	 *
	 * @var integer
	 */
	private $width = 0;

	public function __construct($id, $width, array $classes = [], array $attributes = [])
	{
		parent::__construct($id, $classes, $attributes);

		if ($width > 100) {
			$width = 100;
		}

		$this->width = $width;
	}

	/**
	 * Returns the width of a column in percentage.
	 *
	 * @return number
	 */
	public function getWidth()
	{
		return $this->width;
	}
}
