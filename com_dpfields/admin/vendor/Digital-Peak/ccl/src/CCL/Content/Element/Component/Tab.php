<?php

namespace CCL\Content\Element\Component;

use CCL\Content\Element\Basic\Container;

/**
 * A tab representation.
 */
class Tab extends Container
{

	/**
	 * The name of the tab.
	 *
	 * @var string
	 */
	private $name = '';

	/**
	 * The title of the tab.
	 *
	 * @var string
	 */
	private $title = '';

	public function __construct($id, $name, $title, array $classes = [], array $attributes = [])
	{
		$classes[] = 'ccl-tab';
		$this->setProtectedClass('ccl-tab');

		parent::__construct($id, $classes, $attributes);

		$this->name = $name;
		$this->title = $title;
	}

	/**
	 * Returns the name of the tab.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Returns the title of the tab.
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
}
