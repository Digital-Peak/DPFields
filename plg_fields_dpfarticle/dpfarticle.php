<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR);

class PlgFieldsDPFArticle extends FieldsPlugin
{
	public function onCustomFieldsPrepareDom($field, DOMElement $parent, JForm $form)
	{
		$fieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form);
		if (!$fieldNode) {
			return $fieldNode;
		}

		$form->addFieldPath(JPATH_ADMINISTRATOR . '/components/com_content/models/fields');

		return $fieldNode;

	}
}
