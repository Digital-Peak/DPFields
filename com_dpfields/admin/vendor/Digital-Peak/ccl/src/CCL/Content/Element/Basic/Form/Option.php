<?php

namespace CCL\Content\Element\Basic\Form;

use CCL\Content\Element\Basic\Element;

/**
 * A select option representation.
 */
class Option extends Element
{

	/**
	 * Initiates the label for the id of the given for input.
	 *
	 * @param string $value
	 * @param array $classes
	 * @param array $attributes
	 */
	public function __construct($id, $value, array $classes = [], array $attributes = [])
	{
		$attributes['value'] = $value;
		parent::__construct($id, $classes, $attributes);
	}
}
