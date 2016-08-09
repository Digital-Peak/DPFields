<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2016 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JFormHelper::loadFieldClass('list');
JLoader::import('joomla.filesystem.folder');

class JFormFieldType extends JFormFieldList
{

	public $type = 'Type';

	public function setup (SimpleXMLElement $element, $value, $group = null)
	{
		$return = parent::setup($element, $value, $group);
		$this->onchange = "typeHasChangedDPFields(this);";
		return $return;
	}

	protected function getOptions ()
	{
		$options = parent::getOptions();

		$paths = array();
		$paths[] = JPATH_ADMINISTRATOR . '/components/com_dpfields/models/types';

		if ($this->element['component'])
		{
			$paths[] = JPATH_ADMINISTRATOR . '/components/' . $this->element['component'] . '/models/types';
		}

		foreach ($paths as $path)
		{
			if (! JFolder::exists($path))
			{
				continue;
			}
			// Looping trough the types
			foreach (JFolder::files($path, 'php', false, true) as $filePath)
			{
				$name = str_replace('.php', '', basename($filePath));
				if ($name == 'base')
				{
					continue;
				}

				$label = 'COM_DPFIELDS_TYPE_' . strtoupper($name);
				if (! JFactory::getLanguage()->hasKey($label))
				{
					$label = JString::ucfirst($name);
				}
				$options[] = JHtml::_('select.option', $name, JText::_($label));
			}
		}

		// Sorting the fields based on the text which is displayed
		usort($options, function  ($a, $b)
		{
			return strcmp($a->text, $b->text);
		});

		// Reload the page when the type changes
		$uri = clone JUri::getInstance('index.php');
		// Removing the catid parameter from the actual url and set it as
		// return
		$returnUri = clone JUri::getInstance();
		$returnUri->setVar('catid', null);
		$uri->setVar('return', base64_encode($returnUri->toString()));
		// Setting the options
		$uri->setVar('option', 'com_dpfields');
		$uri->setVar('task', 'field.storeform');
		$uri->setVar('context', 'com_dpfields.field');
		$uri->setVar('formcontrol', $this->form->getFormControl());
		$uri->setVar('userstatevariable', 'com_dpfields.edit.field.data');
		$uri->setVar('view', null);
		$uri->setVar('layout', null);
		JFactory::getDocument()->addScriptDeclaration(
				"function typeHasChangedDPFields(element){
				var cat = jQuery(element);
				jQuery('input[name=task]').val('field.storeform');
				element.form.action='" . $uri . "';
				element.form.submit();
			}");

		return $options;
	}
}
