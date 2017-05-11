<?php

namespace CCL\Content\Element\Basic\Form;

use CCL\Content\Element\Basic\Element;

/**
 * A input representation.
 */
class Input extends Element
{

	/**
	 * Initiates the input for a given type and name.
	 *
	 * @param string $id
	 * @param string $type
	 * @param string $name
	 * @param string $value
	 * @param array $classes
	 * @param array $attributes
	 */
	public function __construct($id, $type, $name, $value = '', array $classes = [], array $attributes = [])
	{
		$attributes['type'] = $type;
		$attributes['name'] = $name;
		$attributes['value'] = $value;

		parent::__construct($id, $classes, $attributes);
	}
}
