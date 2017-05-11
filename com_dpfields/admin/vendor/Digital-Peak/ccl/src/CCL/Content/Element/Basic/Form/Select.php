<?php

namespace CCL\Content\Element\Basic\Form;

use CCL\Content\Element\Basic\Container;

/**
 * A select representation.
 */
class Select extends Container
{

	private $optionCounter = 1;

	/**
	 * Initiates the select for a given name.
	 *
	 * @param string $id
	 * @param string $name
	 * @param boolean $multiple
	 * @param array $classes
	 * @param array $attributes
	 */
	public function __construct($id, $name, $multiple = false, array $classes = [], array $attributes = [])
	{
		$attributes['name']     = $name;

		if ($multiple) {
			$attributes['multiple'] = 'multiple';
		}
		parent::__construct($id, $classes, $attributes);
	}

	/**
	 * Adds an option with the text and value, if selected is true, it gets the selected property.
	 *
	 * @param string $text
	 * @param string $value
	 * @param boolean $selected
	 *
	 * @return \CCL\Content\Element\Basic\Element
	 */
	public function addOption($text, $value, $selected = false)
	{
		$attributes = [];

		if ($selected) {
			$attributes['selected'] = 'selected';
		}
		$option = $this->addChild(new Option($this->getId() . '-' . $this->optionCounter, $value, array(), $attributes));
		$option->setContent($text);

		return $option;
	}
}
