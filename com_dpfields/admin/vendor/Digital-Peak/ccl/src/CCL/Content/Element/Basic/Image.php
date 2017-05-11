<?php

namespace CCL\Content\Element\Basic;

use CCL\Content\Element\Basic\Element;

/**
 * A image representation.
 */
class Image extends Element
{

	/**
	 * Needs a source and an optional alt attribute.
	 *
	 * @param string $id
	 * @param string $src
	 * @param string $alt
	 * @param array $classes
	 * @param array $attributes
	 */
	public function __construct($id, $src, $alt = '', array $classes = [], array $attributes = [])
	{
		$attributes['src'] = $src;
		$attributes['alt'] = $alt;

		parent::__construct($id, $classes, $attributes);
	}
}
