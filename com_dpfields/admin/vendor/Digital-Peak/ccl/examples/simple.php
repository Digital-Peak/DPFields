<?php
/**
 * Simple script to show how to load CCL and to create an element visiting by an example visitor.
 */
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

class ExampleVisitor extends \CCL\Content\Visitor\AbstractElementVisitor
{
	public function visitElement(\CCL\Content\Element\Basic\Element $element)
	{
		echo 'Found element: ' . $element;
	}
}

$element = new \CCL\Content\Element\Basic\Element('demo');
$element->accept(new ExampleVisitor());
