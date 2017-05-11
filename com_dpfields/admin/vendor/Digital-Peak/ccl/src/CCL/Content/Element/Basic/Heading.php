<?php

namespace CCL\Content\Element\Basic;

/**
 * An heading representation.
 */
class Heading extends Container
{

	/**
	 * The heading size 1-6.
	 *
	 * @var integer
	 */
	private $size = 1;

	public function __construct($id, $size, array $classes = [], array $attributes = [])
	{
		if ($size < 1) {
			$size = 1;
		}
		if ($size > 6) {
			$size = 6;
		}

		$this->size = $size;

		parent::__construct($id, $classes, $attributes);
	}

	/**
	 * The size of the heading.
	 *
	 * @return number
	 */
	public function getSize()
	{
		return $this->size;
	}
}
