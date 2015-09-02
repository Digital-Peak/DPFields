<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2007 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class Com_DPFieldsInstallerScript
{

	public function install ($parent)
	{
	}

	public function update ($parent)
	{
	}

	public function uninstall ($parent)
	{
	}

	public function preflight ($type, $parent)
	{
		// Check if the local Joomla version does fit the minimum requirement
		if (version_compare(JVERSION, '3.4') == - 1)
		{
			JFactory::getApplication()->enqueueMessage(
					'This DPFields version does only run on Joomla 3.4 and above, please upgrade your Joomla version!', 'error');
			JFactory::getApplication()->redirect('index.php?option=com_installer&view=install');
			return false;
		}
	}

	public function postflight ($type, $parent)
	{
		if ($type == 'install')
		{
			// Activate the system plugin
			$this->run("update `#__extensions` set enabled=1 where type = 'plugin' and element = 'dpfields'");
		}

		// Will be removed on stable release
		try
		{
			$db = JFactory::getDBO();
			$db->setQuery("ALTER TABLE `#__dpfields_fields` CHANGE `extension` `context` varchar(255) NOT NULL DEFAULT '' AFTER `asset_id`");
			$db->query();
			$db->setQuery("ALTER TABLE `#__dpfields_fields_values` CHANGE `extension` `context` varchar(255) NOT NULL DEFAULT '' AFTER `field_id`");
			$db->query();
		}
		catch (Exception $e)
		{
		}
	}

	private function run ($query)
	{
		try
		{
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$db->query();
		}
		catch (Exception $e)
		{
			echo $e;
		}
	}
}
