<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.vendor.autoload', JPATH_ADMINISTRATOR);

class DPFieldsHelper extends \DPFields\Helper\DPFieldsHelper
{
	/**
	 * Is needed, otherwise the system plugin crashes.
	 *
	 * @param $context
	 *
	 * @return null
	 */
	public static function extract()
	{
		return null;
	}

	public static function render()
	{
		return '';
	}

	public static function getFields()
	{
		return array();
	}
}
