<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('joomla.filesystem.folder');
JFormHelper::loadFieldClass('list');

class JFormFieldGallery extends JFormFieldList
{

	public $type = 'Gallery';

	protected function getOptions ()
	{
		$options = array();

		$path = (string) $this->element['directory'];

		if (! is_dir($path))
		{
			$path = JPATH_ROOT . '/' . $path;
		}

		// Get a list of folders in the search path with the given filter.
		$folders = JFolder::folders($path, '.', true, true);

		// Build the options list from the list of folders.
		if (is_array($folders))
		{
			foreach ($folders as $folder)
			{
				$relativePath = str_replace($path . '/', '', $folder);

				$options[] = JHtml::_('select.option', $relativePath, $relativePath);
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
