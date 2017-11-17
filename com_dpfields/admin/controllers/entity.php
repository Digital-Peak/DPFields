<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use Joomla\Utilities\ArrayHelper;

class DPFieldsControllerEntity extends JControllerForm
{
	private $internalContext;

	public function __construct($config = array())
	{
		parent::__construct($config);

		// An entity edit form can come from the entites or featured view.
		// Adjust the redirect view on the value of 'return' in the request.
		if ($this->input->get('return') == 'featured') {
			$this->view_list = 'featured';
			$this->view_item = 'entity&return=featured';
		}

		$this->internalContext = JFactory::getApplication()->getUserStateFromRequest('com_dpfields.entities.context', 'context');
	}

	protected function allowAdd($data = array())
	{
		$categoryId = ArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');
		$allow      = null;

		if ($categoryId) {
			// If the category has been passed in the data or URL check it.
			$allow = JFactory::getUser()->authorise('core.create', 'com_dpfields.category.' . $categoryId);
		}

		if ($allow === null) {
			// In the absense of better information, revert to the component permissions.
			return parent::allowAdd();
		}

		return $allow;
	}

	protected function allowEdit($data = array(), $key = 'e_id')
	{
		$recordId = (int)isset($data[$key]) ? $data[$key] : 0;
		$user     = JFactory::getUser();

		// Zero record (id:0), return component edit permission by calling parent controller method
		if (!$recordId) {
			return parent::allowEdit($data, $key);
		}

		// Check edit on the record asset (explicit or inherited)
		if ($user->authorise('core.edit', 'com_dpfields.entity.' . $recordId)) {
			return true;
		}

		// Check edit own on the record asset (explicit or inherited)
		if ($user->authorise('core.edit.own', 'com_dpfields.entity.' . $recordId)) {
			// Existing record already has an owner, get it

			if (empty($record)) {
				return false;
			}

			// Grant if current user is owner of the record
			return $user->id == $record->created_by;
		}

		return false;
	}

	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		/** @var DPFieldsModelEntity $model */
		$model = $this->getModel();

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_dpfields&view=entities' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	public function save($key = null, $urlVar = 'e_id')
	{
		$this->context = str_replace('com_dpfields.', '', $this->internalContext);

		return parent::save($key, $urlVar);
	}

	public function edit($key = null, $urlVar = 'e_id')
	{
		return parent::edit($key, $urlVar);
	}

	public function cancel($key = 'e_id')
	{
		return parent::cancel($key);
	}

	public function reload($key = null, $urlVar = 'e_id')
	{
		return parent::reload($key, $urlVar);
	}

	public function getModel($name = 'Entity', $prefix = 'DPFieldsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		$model->setState('entity.context', $this->internalContext);

		return $model;
	}

	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'e_id')
	{
		return parent::getRedirectToItemAppend($recordId, $urlVar) . '&context=' . $this->internalContext;
	}

	protected function getRedirectToListAppend()
	{
		return parent::getRedirectToListAppend() . '&context=' . $this->internalContext;
	}
}
