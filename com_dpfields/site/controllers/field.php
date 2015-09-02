<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsControllerField extends JControllerLegacy
{

	public function catchange ()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$data = $this->input->get($this->input->get('formcontrol', 'jform'), array(), 'array');

		$parts = DPFieldsHelper::extract($this->input->getCmd('context'));
		if ($parts)
		{
			if ((! isset($data['id']) || ! $data['id']) && (isset($data['catid']) && $data['catid']))
			{
				$data['dpfieldscatid'] = $data['catid'];
				$data['catid'] = null;
			}
			$app->setUserState($parts[0] . '.edit.' . $parts[1] . '.data', $data);
		}
		$app->redirect(base64_decode($this->input->get->getBase64('return')));
		$app->close();
	}
}
