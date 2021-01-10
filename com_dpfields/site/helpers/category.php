<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__dpfields_entities';

		if (empty($options['extension'])) {
			$options['extension'] = JFactory::getApplication()->input->get('context', 'com_dpfields%');
		}

		parent::__construct($options);
	}
}
