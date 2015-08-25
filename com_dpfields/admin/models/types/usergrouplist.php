<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.base', JPATH_ADMINISTRATOR);

class DPFieldsTypeUsergrouplist extends DPFieldsTypeBase
{

	public function prepareValueForDisplay ($value, $field)
	{
		$value = (array) $value;
		JLoader::register('UsersHelper', JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php');

		$texts = array();
		foreach (UsersHelper::getGroups() as $group)
		{
			if (in_array($group->value, $value))
			{
				$texts[] = parent::prepareValueForDisplay(trim($group->text, '- '), $field);
			}
		}
		return parent::prepareValueForDisplay($texts, $field);
	}
}
