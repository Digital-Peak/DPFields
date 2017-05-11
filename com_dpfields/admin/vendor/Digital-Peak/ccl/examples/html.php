<?php
/**
 * Simple script to show how to load CCL and to create a HTML string out of an element tree.
 */
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$container = new \CCL\Content\Element\Basic\Container('demo');

// Build the tree
$container->addChild(new \CCL\Content\Element\Basic\Paragraph('child1'))->setContent('Paragraph');
$container->addChild(new \CCL\Content\Element\Basic\TextBlock('child2'))->setContent('TextBlock');

// Traverse the tree
$domBuilder = new \CCL\Content\Visitor\Html\DomBuilder();
$container->accept($domBuilder);

echo $domBuilder->render();
