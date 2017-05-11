<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JFormHelper::loadFieldClass('list');

class JFormFieldDPFields extends JFormFieldList
{
	public $type = 'DPFields';

	protected function getOptions()
	{
		\JLoader::import('components.com_fields.helpers.fields', JPATH_ADMINISTRATOR);
		\JLoader::import('components.com_dpfields.models.contenttypes', JPATH_ADMINISTRATOR);

		$model = new \DPFieldsModelContentTypes();
		$contentTypes = $model->getItems();

		$options = array();
		foreach ($contentTypes as $contentType) {
			$context = 'com_dpfields.' . $contentType->name;

			$fields = FieldsHelper::getFields($context);

			foreach ($fields as $field) {
				$options[] = JHtml::_('select.option', $field->id, JText::_($field->label) . ' [' . $contentType->name . ']');
			}
		}

		// Merge any additional options in the XML definition.
		return array_merge(parent::getOptions(), $options);
	}
}
