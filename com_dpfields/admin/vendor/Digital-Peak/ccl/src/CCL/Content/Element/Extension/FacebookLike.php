<?php

namespace CCL\Content\Element\Extension;

use CCL\Content\Element\Basic\Element;

class FacebookLike extends Element
{

	public function __construct($id, $url, array $classes = [], array $attributes = [])
	{
		$attributes['data-href'] = $url;

		$classes[] = 'fb-like';
		$this->setProtectedClass('fb-like');

		parent::__construct($id, $classes, $attributes);
	}
}
