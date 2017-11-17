<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.controllers.entity', JPATH_ADMINISTRATOR);
JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_dpfields/models');

class DPFieldsControllerEntityForm extends DPFieldsControllerEntity
{

	public function save($key = null, $urlVar = 'e_id')
	{
		$success = parent::save($key);

		$app  = JFactory::getApplication();
		$data = $this->input->post->get('jform', array(), 'array');
		if ($success) {
			if ($this->getTask() == 'save') {
				$app->setUserState("$this->option.edit.$this->context" . '.data', null);
				$return = $this->getReturnPage();
				if ($return == JURI::base()) {
					$return = JRoute::_(
						DPFieldsHelperRoute::getEntityRoute(
							$app->getUserState('dpfields.entity.id'),
							$data['catid'],
							$data['language']
						)
					);
				}

				$this->setRedirect(JRoute::_($return));
			}
			if ($this->getTask() == 'apply' || $this->getTask() == 'save2copy') {
				$return = DPFieldsHelperRoute::getEntityFormRoute($app->getUserState('dpfields.entity.id'),
					$this->getReturnPage());
				$this->setRedirect(JRoute::_($return));
			}
			if ($this->getTask() == 'save2new') {
				$app->setUserState("$this->option.edit.$this->context" . '.data', null);
				$return = DPFieldsHelperRoute::getEntityFormRoute(0, $this->getReturnPage());
				$this->setRedirect(JRoute::_($return));
			}
		} else {
			$this->setRedirect(
				JRoute::_(DPFieldsHelperRoute::getEntityRoute($app->getUserState('dpfields.entity.id'), $data['catid'], $data['language']))
			);
		}

		return $success;
	}

	public function cancel($key = 'e_id')
	{
		$return = parent::cancel($key);
		$this->setRedirect(JRoute::_($this->getReturnPage()));

		return $return;
	}

	public function reload($key = null, $urlVar = 'e_id')
	{
		return parent::reload($key, $urlVar);
	}

	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'e_id')
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);
		$itemId = $this->input->getInt('Itemid');
		$return = $this->getReturnPage();

		if ($itemId) {
			$append .= '&Itemid=' . $itemId;
		}

		if ($return) {
			$append .= '&return=' . base64_encode($return);
		}

		return $append;
	}

	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'default', 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return))) {
			return JURI::base();
		} else {
			return base64_decode($return);
		}
	}
}
