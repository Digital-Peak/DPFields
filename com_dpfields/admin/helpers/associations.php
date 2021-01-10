<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsAssociationsHelper extends JAssociationExtensionHelper
{
	protected $extension = 'com_dpfields';
	protected $itemTypes = array('entity', 'category');
	protected $associationsSupport = true;

	public function getAssociations($typeName, $id)
	{
		$type = $this->getType($typeName);

		$context    = $this->extension . '.entity';
		$catidField = 'catid';

		if ($typeName === 'category') {
			$context    = 'com_categories.item';
			$catidField = '';
		}

		// Get the associations.
		$associations = JLanguageAssociations::getAssociations(
			$this->extension,
			$type['tables']['a'],
			$context,
			$id,
			'id',
			'alias',
			$catidField
		);

		return $associations;
	}

	public function getItem($typeName, $id)
	{
		if (empty($id)) {
			return null;
		}

		$table = null;

		switch ($typeName) {
			case 'entity':
				JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_dpfields/tables');
				$table = JTable::getInstance('Entity', 'DPFieldsTable');
				break;

			case 'category':
				$table = JTable::getInstance('Category');
				break;
		}

		if (is_null($table)) {
			return null;
		}

		$table->load($id);

		return $table;
	}

	public function getType($typeName = '')
	{
		$fields  = $this->getFieldsTemplate();
		$tables  = array();
		$joins   = array();
		$support = $this->getSupportTemplate();
		$title   = '';

		if (in_array($typeName, $this->itemTypes)) {

			switch ($typeName) {
				case 'entity':

					$support['state']     = true;
					$support['acl']       = true;
					$support['checkout']  = true;
					$support['category']  = true;
					$support['save2copy'] = true;

					$tables = array(
						'a' => '#__dpfields_entities'
					);

					$title = 'entity';
					break;

				case 'category':
					$fields['created_user_id'] = 'a.created_user_id';
					$fields['ordering']        = 'a.lft';
					$fields['level']           = 'a.level';
					$fields['catid']           = '';
					$fields['state']           = 'a.published';

					$support['state']    = true;
					$support['acl']      = true;
					$support['checkout'] = true;
					$support['level']    = true;

					$tables = array(
						'a' => '#__categories'
					);

					$title = 'category';
					break;
			}
		}

		return array(
			'fields'  => $fields,
			'support' => $support,
			'tables'  => $tables,
			'joins'   => $joins,
			'title'   => $title
		);
	}
}
