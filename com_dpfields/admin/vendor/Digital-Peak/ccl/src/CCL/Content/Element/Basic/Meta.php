<?php

namespace CCL\Content\Element\Basic;

use CCL\Content\Element\Basic\Element;

/**
 * A meta representation.
 */
class Meta extends Element
{

	/**
	 * Needs a property name and the content of the meta tag.
	 *
	 * @param string $id
	 * @param string $property
	 * @param string $content
	 * @param array $classes
	 * @param array $attributes
	 */
	public function __construct($id, $property, $content, array $classes = [], array $attributes = [])
	{
		$attributes['itemprop'] = $property;
		$attributes['content'] = $content;

		parent::__construct($id, $classes, $attributes);
	}
}
