<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

abstract class DPFieldsHelperRoute
{
	public static function getEntityRoute($id, $catid = 0, $language = 0)
	{
		// Create the link
		$link = 'index.php?option=com_dpfields&view=entity&e_id=' . $id;

		if ((int)$catid > 1) {
			$link .= '&catid=' . $catid;
		}

		if ($language && $language !== '*' && JLanguageMultilang::isEnabled()) {
			$link .= '&lang=' . $language;
		}

		return $link;
	}

	public static function getCategoryRoute($catid, $language = 0)
	{
		if ($catid instanceof JCategoryNode) {
			$id = $catid->id;
		} else {
			$id = (int)$catid;
		}

		if ($id < 1) {
			$link = '';
		} else {
			$link = 'index.php?option=com_dpfields&view=category&id=' . $id;

			if ($language && $language !== '*' && JLanguageMultilang::isEnabled()) {
				$link .= '&lang=' . $language;
			}
		}

		return $link;
	}

	public static function getEntityFormRoute($id, $return)
	{
		if (is_numeric($id)) {
			$id = '&e_id=' . (int)$id;
		} else {
			$id = '&context=' . $id;
		}

		return 'index.php?option=com_dpfields&task=entityform.edit' . $id . '&return=' . base64_encode($return);
	}
}
