<?php
/**
 * @package    DPFields
 * @copyright  (C) 2015 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/**
 * Build release files for the DPFields package.
 */
class DPFieldsReleaseBuild
{

	public function build()
	{
		$buildDir  = dirname(__FILE__);
		$dpVersion = new SimpleXMLElement(file_get_contents(dirname(__FILE__) . '/pkg_dpfields.xml'));
		$dpVersion = (string)$dpVersion->version;

		echo ' Creating version: ' . $dpVersion;

		$dpVersion = str_replace('.', '_', $dpVersion);

		exec('rm -rf ' . $buildDir . '/dist');
		exec('rm -rf ' . $buildDir . '/build');

		mkdir($buildDir . '/dist');
		mkdir($buildDir . '/build');
		$dpDir = $buildDir . '/build/DPFields';
		mkdir($dpDir);

		// Component
		$this->createZip(
			$buildDir . '/../com_dpfields', $dpDir . '/com_dpfields.zip',
			array('com_dpfields/admin/com_dpfields.xml'),
			array('com_dpfields/admin/dpfields.xml' => 'com_dpfields/dpfields.xml')
		);

		// Plugins
		$this->createZip($buildDir . '/../plg_content_dpfields', $dpDir . '/plg_content_dpfields.zip');
		$this->createZip($buildDir . '/../plg_fields_dpfarticle', $dpDir . '/plg_fields_dpfarticle.zip');
		$this->createZip($buildDir . '/../plg_fields_dpfgallery', $dpDir . '/plg_fields_dpfgallery.zip');
		$this->createZip($buildDir . '/../plg_fields_dpfmap', $dpDir . '/plg_fields_dpfmap.zip');
		$this->createZip($buildDir . '/../plg_fields_dpfmedia', $dpDir . '/plg_fields_dpfmedia.zip');
		$this->createZip($buildDir . '/../plg_editors-xtd_dpfields', $dpDir . '/plg_editors-xtd_dpfields.zip');

		// Making the installable zip files
		copy($buildDir . '/license.txt', $dpDir . '/license.txt');
		copy($buildDir . '/script.php', $dpDir . '/script.php');
		copy($buildDir . '/pkg_dpfields.xml', $dpDir . '/pkg_dpfields.xml');

		$this->createZip($dpDir, $buildDir . '/dist/DPFields-Core_' . $dpVersion . '.zip');
	}

	private function createZip($folder, $zipFile, $excludes = array(), $substitutes = array())
	{
		$root = dirname(dirname(__FILE__));

		$zip = new ZipArchive();
		$zip->open($zipFile, ZIPARCHIVE::CREATE);

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder), RecursiveIteratorIterator::LEAVES_ONLY);

		foreach ($files as $name => $file) {
			// Get real path for current file
			$filePath = $file->getRealPath();
			$fileName = str_replace($root . '/', '', $filePath);
			$fileName = str_replace('suite_build/build/DPFields', '', $fileName);

			$ignore = false;
			foreach ($excludes as $exclude) {
				if (strpos($fileName, $exclude) !== false) {
					$ignore = true;
					break;
				}
			}

			if ($ignore || is_dir($filePath)) {
				continue;
			}
			if (key_exists($fileName, $substitutes)) {
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
