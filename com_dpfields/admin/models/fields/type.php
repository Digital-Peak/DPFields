<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JFormHelper::loadFieldClass('list');
JLoader::import('joomla.filesystem.folder');

class JFormFieldType extends JFormFieldList
{

	public $type = 'Type';

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
			foreach (JFolder::files($path, 'php', true, true) as $filePath)
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

		return $options;
	}
}
