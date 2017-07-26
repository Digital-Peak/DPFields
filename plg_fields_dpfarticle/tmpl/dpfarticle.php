<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.Media
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

$value = $field->value;

if ($value == '') {
	return;
}

JLoader::import('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_BASE . '/components/com_content/models', 'ContentModel');
$model = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));
$model->setState('params', new \Joomla\Registry\Registry());
$model->setState('filter.published', 1);

// If the article is not found an error is thrown we need to hold the old error handler
$errorHandler = JError::getErrorHandling(E_ERROR);

// Ignoring all errors
JError::setErrorHandling(E_ERROR, 'ignore');

// Fetching the article
$article = $model->getItem($value);

// Restore the old error handler
JError::setErrorHandling(E_ERROR, $errorHandler['mode'], $errorHandler['options']);

if ($article instanceof JException) {
	return;
}

// Show the title when enabled
if ($fieldParams->get('show_title', 1)) {
	echo htmlentities($article->title);
}

// Show the tags when enabled
if ($fieldParams->get('show_tags', 1)) {
	$tags = new JHelperTags();
	$tags->getItemTags('com_content.article', $article->id);
	echo JLayoutHelper::render('joomla.content.tags', $tags->itemTags);
}

// Show the description when enabled
if ($fieldParams->get('show_intro_image', 1)) {
	echo JLayoutHelper::render('joomla.content.intro_image', $article);
}

// Show the description when enabled
if ($fieldParams->get('show_full_image', 1)) {
	echo JLayoutHelper::render('joomla.content.full_image', $article);
}

// Show the description when enabled
if ($fieldParams->get('show_description', 1)) {
	echo JHtml::_('content.prepare', $article->introtext);
}
