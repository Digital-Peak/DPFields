<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsRouter extends JComponentRouterView
{
	protected $noIDs = true;

	public function __construct($app = null, $menu = null)
	{
		$params = JComponentHelper::getParams('com_dpfields');
		$this->noIDs = (bool)$params->get('sef_ids', true);
		$category = new JComponentRouterViewconfiguration('category');
		$category->setKey('id');
		$this->registerView($category);
		$entity = new JComponentRouterViewconfiguration('entity');
		$entity->setKey('e_id')->setParent($category, 'catid');
		$this->registerView($entity);

		parent::__construct($app, $menu);

		$this->attachRule(new JComponentRouterRulesMenu($this));

		$this->attachRule(new JComponentRouterRulesStandard($this));
		$this->attachRule(new JComponentRouterRulesNomenu($this));
	}

	public function getCategorySegment($id, $query)
	{
		$category = JCategories::getInstance($this->getName())->get($id);

		if ($category) {
			$path = array_reverse($category->getPath(), true);
			$path[0] = '1:root';

			if ($this->noIDs) {
				foreach ($path as &$segment) {
					list($id, $segment) = explode(':', $segment, 2);
				}
			}

			return $path;
		}

		return array();
	}

	public function getCategoriesSegment($id, $query)
	{
		return $this->getCategorySegment($id, $query);
	}

	public function getEntitySegment($id, $query)
	{
		if (!strpos($id, ':')) {
			$db = JFactory::getDbo();
			$dbquery = $db->getQuery(true);
			$dbquery->select($dbquery->qn('alias'))
				->from($dbquery->qn('#__dpfields_entities'))
				->where('id = ' . $dbquery->q($id));
			$db->setQuery($dbquery);

			$id .= ':' . $db->loadResult();
		}

		if ($this->noIDs) {
			list($void, $segment) = explode(':', $id, 2);

			return array($void => $segment);
		}

		return array((int)$id => $id);
	}

	public function getCategoryId($segment, $query)
	{
		if (isset($query['id'])) {
			$category = JCategories::getInstance($this->getName())->get($query['id']);

			foreach ($category->getChildren() as $child) {
				if ($this->noIDs) {
					if ($child->alias == $segment) {
						return $child->id;
					}
				} else {
					if ($child->id == (int)$segment) {
						return $child->id;
					}
				}
			}
		}

		return false;
	}

	public function getCategoriesId($segment, $query)
	{
		return $this->getCategoryId($segment, $query);
	}

	public function getEntityId($segment, $query)
	{
		if ($this->noIDs) {
			$db = JFactory::getDbo();
			$dbquery = $db->getQuery(true);
			$dbquery->select($dbquery->qn('id'))
				->from($dbquery->qn('#__dpfields_entities'))
				->where('alias = ' . $dbquery->q($segment))
				->where('catid = ' . $dbquery->q($query['id']));
			$db->setQuery($dbquery);

			return (int)$db->loadResult();
		}

		return (int)$segment;
	}
}
