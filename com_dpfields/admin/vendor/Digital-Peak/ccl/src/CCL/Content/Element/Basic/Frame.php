<?php

namespace CCL\Content\Element\Basic;

/**
 * A frame representation.
 */
class Frame extends Container
{

	public function __construct($id, $src, array $classes = [], array $attributes = [])
	{
		$attributes['src'] = $src;

		parent::__construct($id, $classes, $attributes);
	}
}
