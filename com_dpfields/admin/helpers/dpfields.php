<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2016 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsHelper
{

	private static $fieldsCache = null;

	private static $fieldCache = null;

	/**
	 * Extracts the component and section from the context string which has to
	 * be in format component.context.
	 *
	 * @param string $contextString
	 * @return array|null
	 */
	public static function extract ($contextString)
	{
		$parts = explode('.', $contextString);
		if (count($parts) < 2)
		{
			return null;
		}
		return $parts;
	}

	/**
	 * Creates an object of the given type.
	 *
	 * @param string $type
	 * @param string $context
	 *
	 * @return DPFieldsTypeBase
	 */
	public static function loadTypeObject ($type, $context)
	{
		// Loading the class
		$class = 'DPFieldsType' . JString::ucfirst($type);
		if (class_exists($class))
		{
			return new $class();
		}

		// Searching the file
		$paths = array(
				JPATH_ADMINISTRATOR . '/components/com_dpfields/models/types'
		);

		if ($context)
		{
			// Extracting the component and section
			$parts = self::extract($context);
			if ($parts)
			{
				$component = $parts[0];

				$paths[] = JPATH_ADMINISTRATOR . '/components/' . $component . '/models/types';
			}
		}

		// Search for the file and load it
		$file = JPath::find($paths, $type . '.php');
		if ($file !== false)
		{
			require_once $file;
		}

		return class_exists($class) ? new $class() : false;
	}

	/**
	 * Returns the fields for the given context.
	 * If the item is an object the returned fields do have an additional field
	 * "value" which represents the value for the given item. If the item has a
	 * assigned_cat_ids field, then additionally fields which belong to that
	 * category will be returned.
	 * Should the value being prepared to be shown in a HTML context
	 * prepareValue must be set to true. Then no further escaping needs to be
	 * don.
	 *
	 * @param string $context
	 * @param stdClass $item
	 * @param boolean $prepareValue
	 * @return array
	 */
	public static function getFields ($context, $item = null, $prepareValue = false)
	{
		if (self::$fieldsCache === null)
		{
			// Load the model
			JLoader::import('joomla.application.component.model');
			JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_dpfields/models', 'DPFieldsModel');
			self::$fieldsCache = JModelLegacy::getInstance('Fields', 'DPFieldsModel', array(
					'ignore_request' => true
			));
			self::$fieldsCache->setState('filter.published', 1);
			self::$fieldsCache->setState('list.limit', 0);
		}

		if (is_array($item))
		{
			$item = (object) $item;
		}

		self::$fieldsCache->setState('filter.language', isset($item->language) ? $item->language : JFactory::getLanguage()->getTag());
		self::$fieldsCache->setState('filter.context', $context);

		// If item has assigned_cat_ids parameter display only fields which
		// belong to the category
		if ($item && (isset($item->catid) || isset($item->dpfieldscatid)))
		{
			$assignedCatIds = isset($item->catid) ? $item->catid : $item->dpfieldscatid;
			self::$fieldsCache->setState('filter.assigned_cat_ids', is_array($assignedCatIds) ? $assignedCatIds : explode(',', $assignedCatIds));
		}

		$fields = self::$fieldsCache->getItems();
		if ($item && isset($item->id))
		{
			if (self::$fieldCache === null)
			{
				self::$fieldCache = JModelLegacy::getInstance('Field', 'DPFieldsModel', array(
						'ignore_request' => true
				));
			}
			$new = array();
			foreach ($fields as $key => $original)
			{
				// Doing a clone, otherwise fields for different items will
				// always reference to the same object
				$field = clone $original;
				$field->value = self::$fieldCache->getFieldValue($field->id, $field->context, $item->id);
				if (! $field->value)
				{
					$field->value = $field->default_value;
				}
				$field->rawvalue = $field->value;

				if ($prepareValue)
				{
					$value = null;

					/*
					 * On before field prepare
					 * Event allow plugins to modfify the output of the field before it is prepared
					 */
					$dispatcher = JEventDispatcher::getInstance();
					$dispatcher->trigger('onDPFieldBeforePrepare', array($context, $item, &$field));

					if ($output = $field->params->get('output'))
					{
						try
						{
							// Load the mustache engine
							JLoader::import('components.com_dpfields.libraries.Mustache.Autoloader', JPATH_ADMINISTRATOR);
							Mustache_Autoloader::register();

							$m = new Mustache_Engine();
							$value = $m->render($output, $field);
						}
						catch (Exception $e)
						{
							JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
						}
					}
					else
					{
						// Is deprecated
						$type = self::loadTypeObject($field->type, $context);
						if ($type)
						{
							$value = $type->prepareValueForDisplay($field->value, $field);
						}
					}

					if (! $value)
					{
						// Prepare the value from the type layout
						$value = self::render($context, 'field.prepare.' . $field->type, array(
								'field' => $field
						));
					}

					// If the value is empty, render the base layout
					if (! $value)
					{
						$value = self::render($context, 'field.prepare.base', array(
								'field' => $field
						));
					}

					/*
					 * On after field render
					 * Event allow plugins to modfify the output of the prepared field
					 */
					$dispatcher->trigger('onDPFieldAfterPrepare', array($context, $item, $field, &$value));
					$field->value = $value;
				}
				$new[$key] = $field;
			}
			$fields = $new;
		}
		return $fields;
	}

	/**
	 * Renders the layout file and data on the context and does a fall back to
	 * DPFields afterwards.
	 *
	 * @param string $context
	 * @param string $layoutFile
	 * @param array $displayData
	 * @return NULL|string
	 */
	public static function render ($context, $layoutFile, $displayData)
	{
		$value = null;

		/*
		 * Because the layout refreshes the paths before the render function is
		 * called, so there is no way to load the layout overrides in the order
		 * template -> context -> dpfields.
		 * If there is no override in the context then we need to call the
		 * layout
		 * from DPFields.
		 */
		if ($parts = self::extract($context))
		{
			// Trying to render the layout on the component fom the context
			$value = JLayoutHelper::render($layoutFile, $displayData, null, array(
					'component' => $parts[0],
					'client' => 0
			));
		}

		if (! $value)
		{
			// Trying to render the layout on DPFields itself
			$value = JLayoutHelper::render($layoutFile, $displayData, null, array(
					'component' => 'com_dpfields',
					'client' => 0
			));
		}

		return $value;
	}

	public static function countItems (&$items)
	{
		$db = JFactory::getDbo();
		foreach ($items as $item)
		{
			$item->count_trashed = 0;
			$item->count_archived = 0;
			$item->count_unpublished = 0;
			$item->count_published = 0;
			$query = $db->getQuery(true);
			$query->select('state, count(*) AS count')
				->from($db->qn('#__dpfields_fields'))
				->where('catid = ' . (int) $item->id)
				->group('state');
			$db->setQuery($query);
			$fields = $db->loadObjectList();
			foreach ($fields as $field)
			{
				if ($field->state == 1)
				{
					$item->count_published = $field->count;
				}
				if ($field->state == 0)
				{
					$item->count_unpublished = $field->count;
				}
				if ($field->state == 2)
				{
					$item->count_archived = $field->count;
				}
				if ($field->state == - 2)
				{
					$item->count_trashed = $field->count;
				}
			}
		}
		return $items;
	}
}
