<?php

namespace CCL\Joomla\Visitor\Html;

use CCL\Content\Element\Basic\Element;
use CCL\Content\Element\Basic\Container;
use CCL\Content\Visitor\AbstractElementVisitor;
use CCL\Content\Visitor\ElementVisitorInterface;
use CCL\Content\Element\ElementInterface;

/**
 * Adds some Joomla specific classes to the content tree.
 */
class Joomla extends AbstractElementVisitor
{

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTable()
	 */
	public function visitTable(\CCL\Content\Element\Basic\Table $table)
	{
		$table->addClass('table-bordered', true);
		$table->addClass('table-hover', true);
	}
}
