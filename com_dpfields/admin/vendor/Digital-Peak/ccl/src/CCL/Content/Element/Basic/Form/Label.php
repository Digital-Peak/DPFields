<?php

namespace CCL\Content\Element\Basic\Form;

use CCL\Content\Element\Basic\Container;

/**
 * A form label representation.
 */
class Label extends Container
{

	/**
	 * Initiates the label for the id of the given for input.
	 *
	 * @param string $forId
	 * @param array $classes
	 * @param array $attributes
	 */
	public function __construct($id, $forId, array $classes = [], array $attributes = [])
	{
		$attributes['for'] = $forId;
		parent::__construct($id, $classes, $attributes);
	}
}
