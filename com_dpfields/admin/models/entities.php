<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use Joomla\Utilities\ArrayHelper;

class DPFieldsModelEntities extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id',
				'a.id',
				'title',
				'a.title',
				'alias',
				'a.alias',
				'checked_out',
				'a.checked_out',
				'checked_out_time',
				'a.checked_out_time',
				'catid',
				'a.catid',
				'category_title',
				'state',
				'a.state',
				'access',
				'a.access',
				'access_level',
				'created',
				'a.created',
				'created_by',
				'a.created_by',
				'created_by_alias',
				'a.created_by_alias',
				'ordering',
				'a.ordering',
				'featured',
				'a.featured',
				'language',
				'a.language',
				'hits',
				'a.hits',
				'publish_up',
				'a.publish_up',
				'publish_down',
				'a.publish_down',
				'published',
				'a.published',
				'author_id',
				'category_id',
				'level',
				'tag'
			);

			if (JLanguageAssociations::isEnabled()) {
				$config['filter_fields'][] = 'association';
			}
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = 'a.title', $direction = 'asc')
	{
		$app = JFactory::getApplication();

		$forcedLanguage = $app->input->get('forcedLanguage', '', 'cmd');

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout')) {
			$this->context .= '.' . $layout;
		}

		// Adjust the context to support forced languages.
		if ($forcedLanguage) {
			$this->context .= '.' . $forcedLanguage;
		}

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access');
		$this->setState('filter.access', $access);

		$authorId = $app->getUserStateFromRequest($this->context . '.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		$level = $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level');
		$this->setState('filter.level', $level);

		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		$tag = $this->getUserStateFromRequest($this->context . '.filter.tag', 'filter_tag', '');
		$this->setState('filter.tag', $tag);

		$search = $this->getUserStateFromRequest($this->context . '.filters_user', 'filters_user', array(), 'array');
		$this->setState('filters_user', $search);

		if ($app->isClient('site')) {
			// Reset list data
			$app->setUserState($this->context . '.list', null);

			$this->filter_fields[] = $app->input->get('filter_order');
		}

		// List state information.
		parent::populateState($ordering, $direction);

		// Force a language
		if (!empty($forcedLanguage)) {
			$this->setState('filter.language', $forcedLanguage);
			$this->setState('filter.forcedLanguage', $forcedLanguage);
		}

		$context = $this->getUserStateFromRequest($this->context . '.context', 'context');
		$this->setState('filter.context', $context);

		$params = JComponentHelper::getParams('com_dpfields');

		if ($app->isClient('site')) {
			$params = $app->getParams();
		}
		$this->setState('params', $params);
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.category_id');
		$id .= ':' . $this->getState('filter.author_id');
		$id .= ':' . $this->getState('filter.language');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$user  = JFactory::getUser();

		// Select the required fields from the table.
		$query->select($this->getState('list.select', 'a.*'));
		$query->from('#__dpfields_entities AS a');

		// Join over the language
		$query->select('l.title AS language_title, l.image AS language_image')
			->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the categories.
		$query->select('c.title AS category_title')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.created_by');

		// Join over the content types for the context.
		$query->select('ua.name AS context')
			->join('LEFT', '#__dpfields_content_types AS ct ON ct.id = a.content_type_id');

		// Join on voting table
		$assogroup = 'a.id, l.title, l.image, uc.name, ag.title, c.title, ua.name';

		if (JPluginHelper::isEnabled('content', 'vote')) {
			$assogroup .= ', v.rating_sum, v.rating_count';
			$query->select('COALESCE(NULLIF(ROUND(v.rating_sum  / v.rating_count, 0), 0), 0) AS rating, 
					COALESCE(NULLIF(v.rating_count, 0), 0) as rating_count')
				->join('LEFT', '#__content_rating AS v ON a.id = v.content_id');
		}

		// Join over the associations.
		if (JLanguageAssociations::isEnabled()) {
			$query->select('COUNT(asso2.id)>1 as association')
				->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_dpfields.entity'))
				->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
				->group($assogroup);
		}

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = ' . (int)$access);
		}

		// Filter by access level on categories.
		if (!$user->authorise('core.admin')) {
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
			$query->where('c.access IN (' . $groups . ')');
		}

		// Filter by context
		$query->where('ct.name = ' . $this->getDbo()->quote(str_replace('com_dpfields.', '', $this->getState('filter.context'))));

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published)) {
			$query->where('a.state = ' . (int)$published);
		} elseif ($published === '') {
			$query->where('(a.state = 0 OR a.state = 1)');
		}

		// Filter by a single or group of categories.
		$baselevel  = 1;
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId)) {
			$categoryTable = JTable::getInstance('Category', 'JTable');
			$categoryTable->load($categoryId);
			$rgt       = $categoryTable->rgt;
			$lft       = $categoryTable->lft;
			$baselevel = (int)$categoryTable->level;
			$query->where('c.lft >= ' . (int)$lft)
				->where('c.rgt <= ' . (int)$rgt);
		} elseif (is_array($categoryId)) {
			$query->where('a.catid IN (' . implode(',', ArrayHelper::toInteger($categoryId)) . ')');
		}

		// Filter on the level.
		if ($level = $this->getState('filter.level')) {
			$query->where('c.level <= ' . ((int)$level + (int)$baselevel - 1));
		}

		// Filter by author
		$authorId = $this->getState('filter.author_id');

		if (is_numeric($authorId)) {
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.created_by ' . $type . (int)$authorId);
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int)substr($search, 3));
			} elseif (stripos($search, 'author:') === 0) {
				$search = $db->quote('%' . $db->escape(substr($search, 7), true) . '%');
				$query->where('(ua.name LIKE ' . $search . ' OR ua.username LIKE ' . $search . ')');
			} else {
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where('(a.title LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')');
			}
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language = ' . $db->quote($language));
		}

		// Filter by a single tag.
		$tagId = $this->getState('filter.tag');

		if (is_numeric($tagId)) {
			$query->where($db->quoteName('tagmap.tag_id') . ' = ' . (int)$tagId)
				->join(
					'LEFT',
					$db->quoteName('#__contentitem_tag_map', 'tagmap')
					. ' ON ' . $db->quoteName('tagmap.content_item_id') . ' = ' . $db->quoteName('a.id')
					. ' AND ' . $db->quoteName('tagmap.type_alias') . ' = ' . $db->quote('com_dpfields.entity')
				);
		}

		$orderCol  = $this->state->get('list.ordering', 'a.title');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if (empty($orderCol)) {
			$orderCol = 'a.title';
		}

		$this->setupFields($query);

		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

		//echo nl2br(str_replace('#__', 'j_', $query));//die;

		return $query;
	}

	private function setupFields(JDatabaseQuery $query)
	{
		$context = $this->getDbo()->quote($this->getState('filter.context'));
		$groups  = implode(',', JFactory::getUser()->getAuthorisedViewLevels());

		// Join over Fields.
		$query->join('LEFT', '#__fields_values AS fv ON fv.item_id = ' . $query->castAsChar('a.id'))
			->join('LEFT', '#__fields AS f ON f.id = fv.field_id')
			->where('(f.context IS NULL OR f.context = ' . $context . ')')
			->where('(f.state IS NULL OR f.state = 1)')
			->where('(f.access IS NULL OR f.access IN (' . $groups . '))')
			->group('a.id');

		// Check if the ordering is based on a field
		$orderCol = $this->getState('list.ordering', 'a.title');
		if (strpos($orderCol, 'jcfield') === 0) {
			// Add the order column as column in the result
			$query->select('fv.value as ' . $this->getDbo()->quoteName($orderCol));
		}

		// Set up the filters
		$filters = $this->getState('params')->get('filters', array());
		$filters = array_merge($this->getState('filters_user'), $filters);

		$condition = '';
		foreach ($filters as $filter) {
			// Can be empty by the user fields when all are deleted
			if (!$filter) {
				continue;
			}

			$operator = $filter['operator'];
			$value    = $filter['value'];

			// Transform the text operator to a like
			if ($operator == 'text') {
				$operator = 'like';
				$value    = str_replace('*', '%', $value);
				$value    = $this->getDbo()->quote($value);
			} else {
				$value = (float)$value;
			}

			// Combine the condition
			if ($condition) {
				$condition .= ' and ';
			}

			// Compile the where clause
			$condition .= '(fv.field_id = ' . (int)$filter['field'];
			$condition .= ' and fv.value ' . $operator . ' ' . $value;
			$condition .= ')';
		}

		if (!$condition) {
			return;
		}

		// Add the condition
		$query->where('(' . $condition . ')');
	}

	public function getAuthors()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('u.id AS value, u.name AS text')
			->from('#__users AS u')
			->join('INNER', '#__dpfields_entities AS c ON c.created_by = u.id')
			->group('u.id, u.name')
			->order('u.name');

		// Setup the query
		$db->setQuery($query);

		// Return the result
		return $db->loadObjectList();
	}

	public function getItems()
	{
		$items = parent::getItems();

		if ($items && JFactory::getApplication()->isClient('site')) {
			$groups = JFactory::getUser()->getAuthorisedViewLevels();

			for ($x = 0, $count = count($items); $x < $count; $x++) {
				// Check the access level. Remove articles the user shouldn't see
				if (!in_array($items[$x]->access, $groups)) {
					unset($items[$x]);
				}
			}
		}

		return $items;
	}

	public function getAdvancedFiltersForm()
	{
		JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_dpfields/models/forms');
		JForm::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_dpfields/models/fields');

		$form = $this->loadForm($this->context . '.filter', 'filters', array('load_data' => true));

		$form->setValue('filters_user', null, JFactory::getApplication()->getUserStateFromRequest($this->context . '.filter', 'filters_user'));

		return $form;
	}

	protected function loadFormData()
	{
		$data = parent::loadFormData();

		// Somehow the repeatable field contains an empty array, it needs to be cleared
		if (!empty($data->filters_user) && key_exists(0, $data->filters_user) && empty($data->filters_user[0])) {
			unset($data->filters_user);
		}

		return $data;
	}

	public function getCategory($id)
	{
		$categoryTable = JTable::getInstance('Category', 'JTable');
		$categoryTable->load($id);

		return $categoryTable;
	}
}
