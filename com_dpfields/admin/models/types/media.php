<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2016 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.base', JPATH_ADMINISTRATOR);

class DPFieldsTypeMedia extends DPFieldsTypeBase
{

	protected function postProcessDomNode ($field, DOMElement $fieldNode, JForm $form)
	{
		$fieldNode->setAttribute('hide_default', 'true');

		if ($field->fieldparams->get('home'))
		{
			$userName = JFactory::getUser()->username;
			$root = $field->fieldparams->get('directory');

			$directory = JPATH_ROOT . '/images/' . trim($root, '/') . '/' . $userName;

			if (!JFolder::exists($directory))
			{
				JFolder::create($directory);
			}

			$fieldNode->setAttribute('directory', str_replace(JPATH_ROOT . '/images', '', $directory));
		}

		return parent::postProcessDomNode($field, $fieldNode, $form);
	}
}
