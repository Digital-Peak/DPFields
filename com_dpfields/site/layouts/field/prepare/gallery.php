<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

if (! key_exists('field', $displayData))
{
	return;
}

$field = $displayData['field'];
$value = $field->value;
if (! $value)
{
	return;
}

// Loading the language
JFactory::getLanguage()->load('com_dpfields', JPATH_ADMINISTRATOR . '/components/com_dpfields');

JHtml::_('jquery.framework');

$doc = JFactory::getDocument();

// Adding the javascript gallery library
$doc->addScript('media/com_dpfields/js/fotorama.min.js');
$doc->addStyleSheet('media/com_dpfields/css/fotorama.min.css');

$value = (array) $value;

$thumbWidth = $field->fieldparams->get('thumbnail_size', '64');

// Main container
$buffer = '<div class="fotorama" data-nav="thumbs" data-width="100%">';
foreach ($value as $path)
{
	// Only process valid paths
	if (! $path)
	{
		continue;
	}

	// The root folder
	$root = $field->fieldparams->get('directory', 'images') . '/' . $path;
	foreach (JFolder::files(JPATH_ROOT . '/' . $root, '.', $field->fieldparams->get('recursive', '1'), true) as $file)
	{
		// Skip none image files
		if (! in_array(strtolower(JFile::getExt($file)), array(
				'jpg',
				'png',
				'bmp',
				'gif'
		)))
		{
			continue;
		}

		// Relative path
		$localPath = str_replace(JPATH_ROOT . '/' . $root . '/', '', $file);

		// Thumbnail path for the image
		$thumb = JPATH_CACHE . '/com_dpfields/gallery/' . $field->id . '/' . $thumbWidth . '/' . $localPath;

		if (! JFile::exists($thumb))
		{
			try
			{
				// Creating the folder structure for the thumbnail
				if (! JFolder::exists(dirname($thumb)))
				{
					JFolder::create(dirname($thumb));
				}

				// Getting the properties of the image
				$properties = JImage::getImageFileProperties($file);
				if ($properties->width > $thumbWidth)
				{
					// Creating the thumbnail for the image
					$imgObject = new JImage($file);
					$imgObject->resize($thumbWidth, 0, false, JImage::SCALE_INSIDE);
					$imgObject->toFile($thumb);
				}
			}
			catch (Exception $e)
			{
				JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_DPFIELDS_TYPE_GALLERY_IMAGE_ERROR', $file, $e->getMessage()));
			}
		}

		if (JFile::exists($thumb))
		{
			// Linking to the real image and loading only the thumbnail
			$buffer .= '<a href="' . $root . '/' . $localPath . '"><img src="' . JUri::base(true) . str_replace(JPATH_ROOT, '', $thumb) . '"/></a>';
		}
		else
		{
			// Thumbnail doesn't exist, loading the full image
			$buffer .= '<img src="' . $root . '/' . $localPath . '"/>';
		}
	}
}
$buffer .= '</div>';

echo $buffer;
