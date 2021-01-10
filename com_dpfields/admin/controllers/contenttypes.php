<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsControllerContentTypes extends JControllerAdmin
{
	public function getModel($name = 'ContentType', $prefix = 'DPFieldsModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}
}
