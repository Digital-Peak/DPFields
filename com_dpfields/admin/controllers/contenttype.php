<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsControllerContentType extends JControllerForm
{
	public function save($key = null, $urlVar = 'c_id')
	{
		return parent::save($key, $urlVar);
	}

	public function edit($key = null, $urlVar = 'c_id')
	{
		return parent::edit($key, $urlVar);
	}

	public function cancel($key = 'c_id')
	{
		return parent::cancel($key);
	}
}
