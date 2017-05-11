<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use Joomla\Registry\Registry;
use Joomla\String\StringHelper;

class DPFieldsTableContentType extends JTable
{

	public function __construct($db = null)
	{
		parent::__construct('#__dpfields_content_types', 'id', $db);

		$this->setColumnAlias('published', 'state');
	}

	public function bind($array, $ignore = '')
	{
		if (isset($array['attribs']) && is_array($array['attribs'])) {
			$registry = new Registry();
			$registry->loadArray($array['attribs']);
			$array['attribs'] = (string)$registry;
		}

		// Bind the rules.
		if (isset($array['rules']) && is_array($array['rules'])) {
			$rules = new JAccessRules($array['rules']);
			$this->setRules($rules);
		}

		return parent::bind($array, $ignore);
	}

	public function check()
	{
		// Check for valid name
		if (trim($this->title) == '') {
			$this->setError(JText::_('COM_FIELDS_LOCATION_ERR_TABLES_TITLE'));
			return false;
		}

		if (empty($this->name)) {
			$this->name = $this->title;
		}
		$this->name = JApplicationHelper::stringURLSafe($this->name);
		if (trim(str_replace('-', '', $this->name)) == '') {
			$this->name = StringHelper::increment($this->name, 'dash');
		}

		$this->name = str_replace(',', '-', $this->name);

		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $this->_db->getNullDate() && $this->publish_down < $this->publish_up) {
			$this->setError(JText::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));
			return false;
		}

		$date = JFactory::getDate();
		$user = JFactory::getUser();
		if ($this->id) {
			// Existing item
			$this->modified = $date->toSql();
			$this->modified_by = $user->get('id');
		} else {
			if (!(int)$this->created) {
				$this->created = $date->toSql();
			}

			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}

		return true;
	}

	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_dpfiels.contenttype.' . (int)$this->$k;
	}

	protected function _getAssetTitle()
	{
		return $this->title;
	}
}
