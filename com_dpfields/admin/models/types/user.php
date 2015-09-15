<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.base', JPATH_ADMINISTRATOR);

class DPFieldsTypeUser extends DPFieldsTypeBase
{

	public function prepareValueForDisplay ($value, $field)
	{
		$value = (array) $value;

		$texts = array();
		foreach ($value as $userId)
		{
			if (! $userId)
			{
				continue;
			}
			$user = JFactory::getUser($userId);
			if ($user)
			{
				$texts[] = $user->name;
			}
			else
			{
				$texts[] = $userId;
			}
		}

		return parent::prepareValueForDisplay($texts, $field);
	}
}
