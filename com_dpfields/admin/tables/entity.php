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

class DPFieldsTableEntity extends JTable
{
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__dpfields_entities', 'id', $db);

		JTableObserverTags::createObserver($this, array('typeAlias' => 'com_dpfields.entity'));
		JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_dpfields.entity'));

		$this->setColumnAlias('published', 'state');
	}

	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return 'com_dpfields.entity.' . (int)$this->$k;
	}

	protected function _getAssetTitle()
	{
		return $this->title;
	}

	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		$assetId = null;

		// This is an article under a category.
		if ($this->catid) {
			// Build the query to get the asset id for the parent category.
			$query = $this->_db->getQuery(true)
				->select($this->_db->quoteName('asset_id'))
				->from($this->_db->quoteName('#__categories'))
				->where($this->_db->quoteName('id') . ' = ' . (int)$this->catid);

			// Get the asset id from the database.
			$this->_db->setQuery($query);

			if ($result = $this->_db->loadResult()) {
				$assetId = (int)$result;
			}
		}

		// Return the asset id.
		if ($assetId) {
			return $assetId;
		} else {
			return parent::_getAssetParentId($table, $id);
		}
	}

	public function bind($array, $ignore = '')
	{
		if (isset($array['attribs']) && is_array($array['attribs'])) {
			$registry = new Registry($array['attribs']);
			$array['attribs'] = (string)$registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata'])) {
			$registry = new Registry($array['metadata']);
			$array['metadata'] = (string)$registry;
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
		if (trim($this->title) == '') {
			$this->setError(JText::_('COM_CONTENT_WARNING_PROVIDE_VALID_NAME'));

			return false;
		}

		if (trim($this->alias) == '') {
			$this->alias = $this->title;
		}

		$this->alias = JApplicationHelper::stringURLSafe($this->alias, $this->language);

		if (trim(str_replace('-', '', $this->alias)) == '') {
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		/**
		 * Ensure any new items have compulsory fields set. This is needed for things like
		 * frontend editing where we don't show all the fields or using some kind of API
		 */
		if (!$this->id) {
			// Attributes (article params) can be an empty json string
			if (!isset($this->attribs)) {
				$this->attribs = '{}';
			}

			// Metadata can be an empty json string
			if (!isset($this->metadata)) {
				$this->metadata = '{}';
			}

			// If we don't have any access rules set at this point just use an empty JAccessRules class
			if (!$this->getRules()) {
				$rules = $this->getDefaultAssetValues('com_dpfields');
				$this->setRules($rules);
			}
		}

		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $this->_db->getNullDate() && $this->publish_down < $this->publish_up) {
			// Swap the dates.
			$temp = $this->publish_up;
			$this->publish_up = $this->publish_down;
			$this->publish_down = $temp;
		}

		// Clean up keywords -- eliminate extra spaces between phrases
		// and cr (\r) and lf (\n) characters from string
		if (!empty($this->metakey)) {
			// Only process if not empty

			// Array of characters to remove
			$bad_characters = array("\n", "\r", "\"", '<', '>');

			// Remove bad characters
			$after_clean = StringHelper::str_ireplace($bad_characters, '', $this->metakey);

			// Create array using commas as delimiter
			$keys = explode(',', $after_clean);

			$clean_keys = array();

			foreach ($keys as $key) {
				if (trim($key)) {
					// Ignore blank keywords
					$clean_keys[] = trim($key);
				}
			}
			// Put array back together delimited by ", "
			$this->metakey = implode(', ', $clean_keys);
		}

		return true;
	}

	protected function getDefaultAssetValues($component)
	{
		// Need to find the asset id by the name of the component.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from($db->quoteName('#__assets'))
			->where($db->quoteName('name') . ' = ' . $db->quote($component));
		$db->setQuery($query);
		$assetId = (int)$db->loadResult();

		return JAccess::getAssetRules($assetId);
	}

	public function store($updateNulls = false)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$this->modified = $date->toSql();

		if ($this->id) {
			// Existing item
			$this->modified_by = $user->get('id');
		} else {
			// New article. An article created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int)$this->created) {
				$this->created = $date->toSql();
			}

			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}

		// Verify that the alias is unique
		$table = JTable::getInstance('Entity', 'DPFieldsTable', array('dbo' => $this->getDbo()));

		if ($table->load(array('alias' => $this->alias, 'catid' => $this->catid)) && ($table->id != $this->id || $this->id == 0)) {
			$this->setError(JText::_('JLIB_DATABASE_ERROR_ARTICLE_UNIQUE_ALIAS'));

			return false;
		}

		return parent::store($updateNulls);
	}
}
