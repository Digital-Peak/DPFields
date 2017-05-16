#!/usr/bin/env php
<?php
$wwwRoot    = '/var/www/j/';
$folderRoot = dirname(__FILE__) . '/../';

echo 'Starting to create the links.' . PHP_EOL;

foreach (new DirectoryIterator($folderRoot) as $filename) {
	if (strpos($filename, 'com_') === 0) {
		createLink($folderRoot . $filename . '/admin', $wwwRoot . 'administrator/components/' . $filename);
		createLink($folderRoot . $filename . '/site', $wwwRoot . 'components/' . $filename);
		createLink($folderRoot . $filename . '/media', $wwwRoot . 'media/' . $filename);
	}
	if (strpos($filename, 'mod_') === 0) {
		createLink($folderRoot . $filename, $wwwRoot . 'modules/' . $filename);
	}
	if (strpos($filename, 'plg_') === 0) {
		foreach (new RegexIterator(new DirectoryIterator($folderRoot . $filename), "/\\.xml\$/i") as $pluginFile) {
			$xml = new SimpleXMLElement(file_get_contents($folderRoot . $filename . '/' . $pluginFile));

			foreach ($xml->files->filename as $file) {
				$plugin = (string)$file->attributes()->plugin;
				if (!$plugin) {
					continue;
				}
				createLink($folderRoot . $filename, $wwwRoot . 'plugins/' . $xml->attributes()->group . '/' . $plugin);

				if (file_exists($folderRoot . $filename . '/media')) {
					createLink($folderRoot . $filename . '/media', $wwwRoot . 'media/' . $filename);
				}
			}
		}
	}
}
echo 'Finished to create the links.' . PHP_EOL;

function createLink($source, $target)
{
	if (is_dir($target)) {
		echo 'Skipped target: ' . $target . PHP_EOL;

		return;
	}


	$source = realpath($source);

	@mkdir(dirname($target), '777', true);
	shell_exec('ln -sf ' . $source . ' ' . $target);
	echo 'Created link from: ' . $source . ' to: ' . $target . PHP_EOL;
}