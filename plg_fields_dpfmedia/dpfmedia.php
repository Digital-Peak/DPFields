<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_fields.libraries.fieldsplugin', JPATH_ADMINISTRATOR);

class PlgFieldsDPFMedia extends FieldsPlugin
{
	public function onCustomFieldsPrepareDom($field, DOMElement $parent, JForm $form)
	{
		$fieldNode = parent::onCustomFieldsPrepareDom($field, $parent, $form);
		if (!$fieldNode) {
			return $fieldNode;
		}

		$fieldNode->setAttribute('hide_default', 'true');

		if ($field->fieldparams->get('home')) {
			$userName = JFactory::getUser()->username;
			$root     = $field->fieldparams->get('directory');
			if (!$root) {
				$root = 'images';
			}
			$directory = JPATH_ROOT . '/images/' . $root . '/' . $userName;
			if (!JFolder::exists($directory)) {
				JFolder::create($directory);
			}
			$fieldNode->setAttribute('directory', str_replace(JPATH_ROOT . '/images', '', $directory));
		}

		return $fieldNode;

	}
}
