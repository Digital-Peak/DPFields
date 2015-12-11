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

JHtml::_('jquery.framework');

$doc = JFactory::getDocument();
$doc->addScript('media/com_dpfields/js/fotorama.min.js');
$doc->addStyleSheet('media/com_dpfields/css/fotorama.min.css');

$content = '';
$doc->addScriptDeclaration($content);

$value = (array) $value;

$thumbWidth = $field->fieldparams->get('thumbnail_size', '64');

$buffer = '<div class="fotorama" data-nav="thumbs" data-width="100%">';
foreach ($value as $path)
{
	if (! $path)
	{
		continue;
	}

	$root = $field->fieldparams->get('directory', 'images') . '/' . $path;
	foreach (JFolder::files(JPATH_ROOT . '/' . $root, '.', $field->fieldparams->get('recursive', '1'), true) as $file)
	{
		$localPath = str_replace(JPATH_ROOT . '/' . $root . '/', '', $file);
		$thumb = JPATH_CACHE . '/dpfields/gallery/' . $field->id . '/' . $thumbWidth . '/' . $localPath;

		if (! JFile::exists($thumb))
		{
			if (! JFolder::exists(dirname($thumb)))
			{
				JFolder::create(dirname($thumb));
			}
			$properties = JImage::getImageFileProperties($file);
			if ($properties->width > $thumbWidth)
			{
				$imgObject = new JImage($file);
				$imgObject->resize($thumbWidth, 0, false, JImage::SCALE_INSIDE);
				$imgObject->toFile($thumb);
			}
		}
		if (JFile::exists($thumb))
		{
			$buffer .= '<a href="' . $root . '/' . $localPath . '"><img src="' . str_replace(JPATH_ROOT, '', $thumb) . '"/></a>';
		}
		else
		{
			$buffer .= '<img src="' . $root . '/' . str_replace(JPATH_ROOT . '/' . $root . '/', '', $file) . '"/>';
		}
	}
}
$buffer .= '</div>';

echo $buffer;
