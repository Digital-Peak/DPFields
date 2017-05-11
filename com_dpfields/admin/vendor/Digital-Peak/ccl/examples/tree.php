<?php
/**
 * Simple script to show how to load CCL and to create an element tree.
 */
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

class ExampleVisitor extends \CCL\Content\Visitor\AbstractElementVisitor
{
	public function visitParagraph(\CCL\Content\Element\Basic\Paragraph $p)
	{
		$this->printElement($p);
	}

	public function visitTextBlock(\CCL\Content\Element\Basic\TextBlock $t)
	{
		$this->printElement($t);
	}

	private function printElement(\CCL\Content\Element\ElementInterface $element)
	{
		echo 'Found element: ' . $element . ' with content: ' . $element->getContent() . PHP_EOL;
	}
}

$container = new \CCL\Content\Element\Basic\Container('demo');

// Build the tree
$container->addChild(new \CCL\Content\Element\Basic\Paragraph('child1'))->setContent('Paragraph');
$container->addChild(new \CCL\Content\Element\Basic\TextBlock('child2'))->setContent('TextBlock');

// Traverse the tree
$container->accept(new ExampleVisitor());
