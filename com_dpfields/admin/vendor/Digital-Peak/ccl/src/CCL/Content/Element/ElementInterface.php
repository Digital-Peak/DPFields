<?php

namespace CCL\Content\Element;

use CCL\Content\Visitor\ElementVisitorInterface;

/**
 * Interface which defines an element.
 */
interface ElementInterface
{
	/**
	 * Returns the id of the element.
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * Returns the content of the element.
	 *
	 * @return string
	 */
	public function getContent();

	/**
	 * Returns the attributes of the element.
	 *
	 * @return string
	 */
	public function getAttributes();

	/**
	 * Returns the parent of the element.
	 *
	 * @return \CCL\Content\Element\ElementInterface
	 */
	public function getParent();

	/**
	 * Accepts the visitor and is calling the aproperiate visit method.
	 *
	 * @param ElementVisitorInterface $visitor
	 */
	public function accept(ElementVisitorInterface $visitor);
}
