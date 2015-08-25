<?php

/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/**
 * Build release files for the DPFields package.
 */
class DPFieldsReleaseBuild
{

	public function build ()
	{
		$root = dirname(dirname(__FILE__));
		$buildDir = dirname(__FILE__);
		$dpVersion = new SimpleXMLElement(file_get_contents(dirname(__FILE__) . '/pkg_dpfields.xml'));
		$dpVersion = (string) $dpVersion->version;

		$dpVersion = str_replace('.', '_', $dpVersion);

		exec('rm -rf ' . $buildDir . '/dist');
		exec('rm -rf ' . $buildDir . '/build');

		mkdir($buildDir . '/dist');
		mkdir($buildDir . '/build');
		$dpDir = $buildDir . '/build/DPFields';
		mkdir($dpDir);

		// Component
		$this->createZip($buildDir . '/../com_dpfields', $dpDir . '/com_dpfields.zip', array(
				'com_dpfields/admin/com_dpfields.xml'
		), array(
				'com_dpfields/admin/dpfields.xml' => 'com_dpfields/dpfields.xml'
		));

		// Plugins
		$this->createZip($buildDir . '/../plg_system_dpfields', $dpDir . '/plg_system_dpfields.zip');

		// Making the installable zip files
		copy($buildDir . '/license.txt', $dpDir . '/license.txt');
		copy($buildDir . '/pkg_dpfields.xml', $dpDir . '/pkg_dpfields.xml');

		$this->createZip($dpDir, $buildDir . '/dist/DPFields-Core_' . $dpVersion . '.zip');
	}

	private function createZip ($folder, $zipFile, $excludes = array(), $substitutes = array())
	{
		$root = dirname(dirname(__FILE__));

		$zip = new ZipArchive();
		$zip->open($zipFile, ZIPARCHIVE::CREATE);

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder), RecursiveIteratorIterator::LEAVES_ONLY);

		foreach ($files as $name => $file)
		{
			// Get real path for current file
			$filePath = $file->getRealPath();
			$fileName = str_replace($root . '/', '', $filePath);
			$fileName = str_replace('suite_build/build/DPFields', '', $fileName);

			$ignore = false;
			foreach ($excludes as $exclude)
			{
				if (strpos($fileName, $exclude) !== false)
				{
					$ignore = true;
					break;
				}
			}

			if ($ignore || is_dir($filePath))
			{
				continue;
			}
			if (key_exists($fileName, $substitutes))
			{
				$fileName = $substitutes[$fileName];
			}

			$fileName = trim($fileName, '/');

			// Add current file to archive
			$zip->addFile($filePath, $fileName);
		}

		$zip->close();
	}
}

$build = new DPFieldsReleaseBuild();
$build->build();
