<?php

namespace CCL\Content\Element\Basic;

/**
 * A link representation.
 */
class Link extends Container
{

	public function __construct($id, $link, $target = null, array $classes = [], array $attributes = [])
	{
		parent::__construct($id, $classes, $attributes);

		$this->addAttribute('href', $link);

		if ($target) {
			$this->addAttribute('target', $target);
		}
	}
}
