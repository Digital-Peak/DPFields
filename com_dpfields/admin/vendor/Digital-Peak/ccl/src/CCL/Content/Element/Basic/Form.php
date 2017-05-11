<?php

namespace CCL\Content\Element\Basic;

class Form extends Container
{

	/**
	 * Form constructor.
	 *
	 * @param string $id
	 * @param array  $action
	 * @param array  $name
	 * @param string $method
	 * @param array  $classes
	 * @param array  $attributes
	 */
	public function __construct($id, $action, $name, $method = 'POST', array $classes = [], array $attributes = [])
	{
		$attributes['action'] = $action;
		$attributes['name']   = $name;
		$attributes['method'] = $method;

		parent::__construct($id, $classes, $attributes);
	}
}
