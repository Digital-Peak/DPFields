<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.base', JPATH_ADMINISTRATOR);

class DPFieldsTypeEditor extends DPFieldsTypeBase
{

	public function prepareValueForDisplay ($value, $field)
	{
		return JHTML::_('content.prepare', $value);
	}

	protected function postProcessDomNode ($field, DOMElement $fieldNode, JForm $form)
	{
		$fieldNode->setAttribute('buttons', $field->fieldparams->get('buttons', 0) ? 'true' : 'false');
		$fieldNode->setAttribute('filter', 'JComponentHelper::filterText');

		return parent::postProcessDomNode($field, $fieldNode, $form);
	}
}
