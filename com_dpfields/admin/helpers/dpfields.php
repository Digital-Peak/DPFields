<?php
/**
 * @package    DPFields
 * @copyright  (C) 2015 Digital Peak GmbH. <https://www.digital-peak.com>
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
