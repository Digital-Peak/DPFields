<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsViewEntity extends \DPFields\View\BaseView
{
	public function display($tpl = null)
	{
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_dpfields/models');
		$model = JModelLegacy::getInstance('Entity', 'DPFieldsModel');
		$this->setModel($model, true);

		return parent::display($tpl);
	}

	public function init()
	{
		$this->entity = $this->get('Item');

		$this->contentType = $this->getModel()->getContentType($this->entity->content_type_id);
		$context           = 'com_dpfields.' . $this->contentType->name;

		// The category of the entity
		$this->category = JCategories::getInstance('DPFields', array('extension' => $context))->get($this->entity->catid);

		$this->entity->tags = new JHelperTags;
		$this->entity->tags->getItemTags('com_dpfields.entity', $this->entity->id);

		$this->app->triggerEvent(
			'onContentPrepare',
			array($context, &$this->entity, &$this->entity->params, 0)
		);
	}
}
