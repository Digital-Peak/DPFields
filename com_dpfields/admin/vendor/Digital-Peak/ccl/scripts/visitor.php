<?php
$root = dirname(__DIR__) . "/src/CCL/Content/Element/";

$files = [];
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {

	if ($file->isDir() || $file->getFileName() == 'ElementInterface.php') {
		continue;
	}


	$name = str_replace(['.php', $root], '', $file->getPathName());
	$name = str_replace("Basic" . DIRECTORY_SEPARATOR, "", $name);
	$name = str_replace("Component" . DIRECTORY_SEPARATOR, "", $name);
	$name = str_replace("Extension" . DIRECTORY_SEPARATOR, "", $name);
	$name = str_replace(DIRECTORY_SEPARATOR, "", $name);

	$files[$name] = $file->getPathName();
}

ksort($files);

$functions  = array();
$functionsI = array();

foreach ($files as $name => $file) {
	$content = file_get_contents($file);
	if (!preg_match("#^namespace\s+(.+?);$#sm", $content, $m)) {
		continue;
	}

	$namespace = "\\" . $m[1] . "\\" . basename($file, ".php");

	$functions[] = "	/**
	 * {@inheritdoc}
	 *
	 * @see \\CCL\\Content\\Visitor\\ElementVisitorInterface::visit" . $name . "()
	 */
	public function visit" . $name . "(" . $namespace . " \$" . lcfirst($name) . ")
	{
	}
";

	$functionsI[] = "	/**
	 * Visit the " . $name . "
	 *
	 * @param " . $namespace . " \$" . lcfirst($name) . "
	 */
	public function visit" . $name . "(" . $namespace . " \$" . lcfirst($name) . ");
";
}

$buffer = "<?php

namespace CCL\\Content\\Visitor;

/**
 * Abstract class which implements ElementVisitorInterface.
 */
abstract class AbstractElementVisitor implements ElementVisitorInterface
{

" . implode(PHP_EOL, $functions) . "}
";

file_put_contents(dirname(__DIR__) . "/src/CCL/Content/Visitor/AbstractElementVisitor.php", $buffer);

$buffer = "<?php

namespace CCL\\Content\\Visitor;

/**
 * Interface to visit the elements.
 */
interface ElementVisitorInterface
{

" . implode(PHP_EOL, $functionsI) . "}
";
file_put_contents(dirname(__DIR__) . "/src/CCL/Content/Visitor/ElementVisitorInterface.php", $buffer);
