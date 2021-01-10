<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace DPFields\Helper;

defined('_JEXEC') or die();

use CCL\Content\Element\ElementInterface;
use CCL\Content\Visitor\Html\DomBuilder;
use CCL\Content\Visitor\Html\LinkHandler;
use CCL\Joomla\Visitor\Html\IconStrategy\Joomla;

class DPFieldsHelper
{
	public static function addSubmenu($vName = 'contenttypes', $contentType = null)
	{
		\JHtmlSidebar::addEntry(\JText::_('COM_DPFIELDS_CONTENT_TYPES'), 'index.php?option=com_dpfields&view=contenttypes', $vName == 'contenttypes');

		\JLoader::import('components.com_dpfields.models.contenttypes', JPATH_ADMINISTRATOR);
		$model        = new \DPFieldsModelContentTypes(array('ignore_request' => true));
		$contentTypes = $model->getItems();

		// Loop over the content types
		foreach ($contentTypes as $type) {
			$title = \JText::_($type->title);

			\JHtmlSidebar::addEntry(
				$title,
				'index.php?option=com_dpfields&view=entities&context=com_dpfields.' . $type->name, $vName == 'entities' && $contentType == $type->name
			);
			\JHtmlSidebar::addEntry(
				$title . ' ' . \JText::_('JCATEGORIES'),
				'index.php?option=com_categories&extension=com_dpfields.' . $type->name, $vName == 'categories' && $contentType == $type->name
			);
		}

		if ($contentTypes) {
			\JHtmlSidebar::addEntry(
				\JText::_('JGLOBAL_FIELDS'),
				'index.php?option=com_fields&context=com_dpfields.' . $contentTypes[0]->name, $vName == 'fields.fields'
			);
			\JHtmlSidebar::addEntry(
				\JText::_('JGLOBAL_FIELD_GROUPS'),
				'index.php?option=com_fields&view=groups&context=com_dpfields.' . $contentTypes[0]->name, $vName == 'fields.groups'
			);
		}
	}

	public static function getContexts()
	{
		\JLoader::import('components.com_dpfields.models.contenttypes', JPATH_ADMINISTRATOR);
		\JFactory::getLanguage()->load('com_dpfields', JPATH_ADMINISTRATOR);

		$contexts = array();

		$model        = new \DPFieldsModelContentTypes();
		$contentTypes = $model->getItems();

		// Loop over the content types
		foreach ($contentTypes as $index => $type) {
			$contexts['com_dpfields.' . $type->name]                 = \JText::_($type->title);
			$contexts['com_dpfields.' . $type->name . '.categories'] = \JText::_($type->title) . ' ' . \JText::_('JCATEGORY');
		}

		return $contexts;
	}

	public static function renderLayout($layout, $data = array())
	{
		return \JLayoutHelper::render($layout, $data, null, array('component' => 'com_dpfields', 'client' => 0));
	}

	public static function renderElement(ElementInterface $rootElement, $params)
	{
		try {
			// Load the front end framework
			$className = 'CCL\\Content\\Visitor\\Html\\Framework\\' . $params->get('frontend_framework', 'BS2');
			if (class_exists($className)) {
				$rootElement->accept(new $className());
			}

			// Load the icon set
			$className = 'CCL\\Content\\Visitor\\Html\\IconStrategy\\' . $params->get('icon_framework', 'Joomla');

			if ($params->get('icon_framework', 'Joomla') == 'Joomla') {
				$className = Joomla::class;
			}

			if (class_exists($className)) {
				$rootElement->accept(new $className());
			}

			// Add the link handler
			$rootElement->accept(new LinkHandler());

			// Add the joomla styler
			$rootElement->accept(new \CCL\Joomla\Visitor\Html\Joomla());

			$builder = new DomBuilder();
			$rootElement->accept($builder);

			// Render the tree
			return $builder->render();
		} catch (\Exception $e) {
			$message = $e->getMessage();

			if (JDEBUG) {
				// $message .= PHP_EOL . $e->getTraceAsString();
			}

			// Display the exception
			\JFactory::getApplication()->enqueueMessage(nl2br($message), 'error');

			return '';
		}
	}
}
