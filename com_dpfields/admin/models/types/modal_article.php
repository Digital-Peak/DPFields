<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.models.types.base', JPATH_ADMINISTRATOR);

class DPFieldsTypeModal_Article extends DPFieldsTypeBase
{

	public function prepareValueForDisplay ($value, $field)
	{
		JLoader::import('joomla.application.component.model');
		JModelLegacy::addIncludePath(JPATH_BASE . '/components/com_content/models', 'ContentModel');
		$model = JModelLegacy::getInstance('Article', 'ContentModel');

		// If the article is not found an error is thrown we need to hold the
		// old error handler
		$errorHandler = JError::getErrorHandling(E_ERROR);

		// Ignoring all errors
		JError::setErrorHandling(E_ERROR, 'ignore');

		// Fetching the article
		$article = $model->getItem($value);

		// Restoreing the old error handler
		JError::setErrorHandling(E_ERROR, $errorHandler['mode'], $errorHandler['options']);

		if ($article instanceof JException)
		{
			return '';
		}
		return parent::prepareValueForDisplay($article->title, $field);
	}

	protected function postProcessDomNode ($field, DOMElement $fieldNode, JForm $form)
	{
		$form->addFieldPath(JPATH_ADMINISTRATOR . '/components/com_content/models/fields');
	}
}
