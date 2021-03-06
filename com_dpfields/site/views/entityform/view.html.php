<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsViewEntityForm extends \DPFields\View\LayoutView
{
	protected $layoutName = 'entity.form.default';

	public function init()
	{
		$this->form       = $this->get('Form');
		$this->entity     = $this->get('Item');
		$this->returnPage = $this->get('ReturnPage');
	}

	public function display($tpl = null)
	{
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_dpfields/models');
		$model = JModelLegacy::getInstance('Entity', 'DPFieldsModel');
		$this->setModel($model, true);

		return parent::display($tpl);
	}
}
