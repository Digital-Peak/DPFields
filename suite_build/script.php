<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class Pkg_DPFieldsInstallerScript
{

	public function install($parent)
	{
	}

	public function update($parent)
	{
	}

	public function uninstall($parent)
	{
	}

	public function preflight($type, $parent)
	{
		// Check if the local Joomla version does fit the minimum requirement
		if (version_compare(JVERSION, '3.7') == -1) {
			JFactory::getApplication()->enqueueMessage(
				'This DPFields version does only run on Joomla 3.7 and above, please upgrade your Joomla version or install an older version of DPFields!',
				'error');
			JFactory::getApplication()->redirect('index.php?option=com_installer&view=install');

			return false;
		}

		return true;
	}

	public function postflight($type, $parent)
	{
	}
}
