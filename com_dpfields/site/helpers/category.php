<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__dpfields_entities';

		if (empty($options['extension'])) {
			$options['extension'] = 'com_dpfields%';
		}

		parent::__construct($options);
	}
}
