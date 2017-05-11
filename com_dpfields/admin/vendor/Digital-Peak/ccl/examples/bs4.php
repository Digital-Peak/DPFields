<?php
/**
 * Simple script to show how to load CCL and to create a HTML string out of an element tree.
 */
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$container = new \CCL\Content\Element\Basic\Container('demo');

// Build the tree
$container->addChild(new \CCL\Content\Element\Basic\Paragraph('child'))->setContent('Paragraph');
$container->addChild(new \CCL\Content\Element\Component\Alert('alert'))->setContent('I am an alert box!');

$container->accept(new \CCL\Content\Visitor\Html\Framework\BS4());

// Traverse the tree
$domBuilder = new \CCL\Content\Visitor\Html\DomBuilder();
$container->accept($domBuilder);

echo $domBuilder->render();
