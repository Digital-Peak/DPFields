<?php

namespace CCL\Content\Visitor\Html;

use CCL\Content\Element\Basic\Element;
use CCL\Content\Element\Basic\Container;
use CCL\Content\Visitor\AbstractElementVisitor;
use CCL\Content\Visitor\ElementVisitorInterface;
use CCL\Content\Element\ElementInterface;

/**
 * Manipulates links.
 */
class LinkHandler extends AbstractElementVisitor
{
	/**
	 * The root.
	 *
	 * @var string
	 */
	private $root = null;

	/**
	 * If the given root parameter is set, then it checks if the link belongs to this domain. If not a rel nofollow attribute is added.
	 *
	 * @param string $root
	 */
	public function __construct($root = null)
	{
		$this->root = $root;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitLink()
	 */
	public function visitLink(\CCL\Content\Element\Basic\Link $link)
	{
		// Check if the root is something to work with
		if (!$this->root) {
			return;
		}

		// Get the url out of the link
		$url = $link->getAttributes()['href'];

		// Check if the url of the link belongs to the root
		if (strpos($url, $this->root) === false) {
			$link->addAttribute('rel', 'nofollow');
		}
	}
}
