<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace DPFields\View;

defined('_JEXEC') or die();

use DPFields\Helper\DPFieldsHelper;

class LayoutView extends BaseView
{
	protected $layoutName = null;

	public function loadTemplate($tpl = null)
	{
		if (!$this->layoutName) {
			return parent::loadTemplate($tpl);
		}

		if (!isset($this->returnPage)) {
			$this->returnPage = '';
		}

		return DPFieldsHelper::renderLayout($this->layoutName, $this->getLayoutData(), null, array('client' => 0, 'component' => 'com_dpfields'));
	}

	protected function getLayoutData()
	{
		$data = [];

		foreach (get_object_vars($this) as $name => $var) {
			if (strpos($name, '_') === 0) {
				continue;
			}
			$data[$name] = $var;
		}

		return $data;
	}
}
