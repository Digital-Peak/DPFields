<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2015 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use Joomla\Registry\Registry;

JLoader::import('joomla.filesystem.folder');
JLoader::import('joomla.filesystem.file');

if (! JFile::exists(JPATH_ADMINISTRATOR . '/components/com_dpfields/helpers/dpfields.php'))
{
	return;
}

JLoader::register('DPFieldsHelper', JPATH_ADMINISTRATOR . '/components/com_dpfields/helpers/dpfields.php');

class PlgSystemDPFields extends JPlugin
{

	const DEFAULT_HIDE_ALIAS = 'disable-default-rendering';

	protected $autoloadLanguage = true;

	private $supportedContexts;

	public function __construct ($subject, $config)
	{
		parent::__construct($subject, $config);

		$this->supportedContexts = array();

		foreach (explode(PHP_EOL,
				$this->params->get('contexts',
						'com_content=article,category,form' . PHP_EOL . 'com_users=user,profile' . PHP_EOL . 'com_modules=module')) as $entry)
		{
			$parts = explode('=', trim($entry));
			if (count($parts) < 2)
			{
				continue;
			}
			$this->supportedContexts[$parts[0]] = $parts[1];
		}
	}

	public function onAfterRoute ()
	{
		if (! $this->isComponentAvailable())
		{
			return;
		}

		$app = JFactory::getApplication();
		$input = $app->input;

		// Only add entries on back end
		if (! $app->isAdmin())
		{
			return;
		}
		$component = $input->getCmd('option');

		// Define the component and section of the context to support
		$section = '';
		if ($component == 'com_dpfields' || $component == 'com_categories')
		{
			$context = $input->getCmd($component == 'com_dpfields' ? 'context' : 'extension');
			$parts = $this->getParts($context);
			if (! $parts)
			{
				$component = $context;
			}
			else
			{
				$component = $parts[0];
				$section = $parts[1];
			}
		}

		// Only do supported contexts
		if (! key_exists($component, $this->supportedContexts))
		{
			return;
		}

		if (! $section)
		{
			$section = $this->supportedContexts[$component];
			$sections = explode(',', $section);
			$section = reset($sections);
		}

		if ($component == 'com_modules')
		{
			// Add link to modules list as they don't have a navigation menu
			JFactory::getLanguage()->load('com_modules');
			JHtmlSidebar::addEntry(JText::_('COM_MODULES_MODULES'), 'index.php?option=com_modules', $input->getCmd('option') == 'com_modules');
		}

		// Add the fields entry
		JHtmlSidebar::addEntry(JText::_('PLG_SYSTEM_DPFIELDS_FIELDS'), 'index.php?option=com_dpfields&context=' . $component . '.' . $section,
				$input->getCmd('option') == 'com_dpfields');
		JHtmlSidebar::addEntry(JText::_('PLG_SYSTEM_DPFIELDS_FIELD_CATEGORIES'),
				'index.php?option=com_categories&extension=' . $component . '.' . $section . '.fields',
				$input->getCmd('extension') == $component . '.' . $section . '.fields');
	}

	public function onContentBeforeSave ($context, $item, $isNew)
	{
		if (! $this->isComponentAvailable())
		{
			return true;
		}

		// Load the category context based on the extension
		if ($context == 'com_categories.category')
		{
			$context = JFactory::getApplication()->input->getCmd('extension') . '.category';
		}

		$parts = $this->getParts($context);
		if (! $parts)
		{
			return true;
		}
		$context = $parts[0] . '.' . $parts[1];

		// Loading the fields
		$fields = DPFieldsHelper::getFields($context, $item);
		if (! $fields)
		{
			return true;
		}

		$params = new Registry();

		// Load the item params from the request
		$data = JFactory::getApplication()->input->post->get('jform', array(), 'array');
		if (key_exists('params', $data))
		{
			$params->loadArray($data['params']);
		}

		// Load the params from the item itself
		if (isset($item->params))
		{
			$params->loadString($item->params);
		}
		$params = $params->toArray();

		if (! $params)
		{
			return true;
		}

		// Create the new internal dpfields field
		$dpfields = array();
		foreach ($fields as $field)
		{
			// Only safe the fields with the alias from the data
			if (! key_exists($field->alias, $params))
			{
				continue;
			}

			// Set the param on the dpfields variable
			$dpfields[$field->alias] = $params[$field->alias];

			// Remove it from the params array
			unset($params[$field->alias]);
		}

		$item->_dpfields = $dpfields;

		// Update the cleaned up params
		if (isset($item->params))
		{
			$item->params = json_encode($params);
		}
	}

	public function onContentAfterSave ($context, $item, $isNew)
	{
		if (! $this->isComponentAvailable())
		{
			return true;
		}

		// Load the category context based on the extension
		if ($context == 'com_categories.category')
		{
			$context = JFactory::getApplication()->input->getCmd('extension') . '.category';
		}

		$parts = $this->getParts($context);
		if (! $parts)
		{
			return true;
		}
		$context = $parts[0] . '.' . $parts[1];

		// Return if the item has no valid state
		$dpfields = null;
		if (isset($item->_dpfields))
		{
			$dpfields = $item->_dpfields;
		}

		if (! $dpfields)
		{
			return true;
		}

		// Loading the fields
		$fields = DPFieldsHelper::getFields($context, $item);
		if (! $fields)
		{
			return true;
		}

		// Loading the model
		$model = JModelLegacy::getInstance('Field', 'DPFieldsModel', array(
				'ignore_request' => true
		));
		foreach ($fields as $field)
		{
			// Only safe the fields with the alias from the data
			if (! key_exists($field->alias, $dpfields))
			{
				continue;
			}

			$id = null;
			if (isset($item->id))
			{
				$id = $item->id;
			}

			if (! $id)
			{
				continue;
			}

			// Setting the value for the field and the item
			$model->setFieldValue($field->id, $context, $id, $dpfields[$field->alias]);
		}

		return true;
	}

	public function onExtensionBeforeSave ($context, $item, $isNew)
	{
		return $this->onContentBeforeSave($context, $item, $isNew);
	}

	public function onExtensionAfterSave ($context, $item, $isNew)
	{
		return $this->onContentAfterSave($context, $item, $isNew);
	}

	public function onUserAfterSave ($userData, $isNew, $success, $msg)
	{
		if (! $this->isComponentAvailable())
		{
			return true;
		}

		// It is not possible to manipulate the user during save events
		// http://joomla.stackexchange.com/questions/10693/changing-user-group-in-onuserbeforesave-of-user-profile-plugin-doesnt-work

		// Check if data is valid or we are in a recursion
		if (! $userData['id'] || ! $success)
		{
			return true;
		}

		$user = JFactory::getUser($userData['id']);
		$user->params = (string) $user->getParameters();

		// Trigger the events with a real user
		$this->onContentBeforeSave('com_users.user', $user, false);
		$this->onContentAfterSave('com_users.user', $user, false);

		// Save the user with the modifed params
		$db = JFactory::getDbo();
		$db->setQuery('update #__users set params = ' . $db->q($user->params));
		$db->query();

		return true;
	}

	public function onContentAfterDelete ($context, $item)
	{
		if (! $this->isComponentAvailable())
		{
			return true;
		}

		$parts = $this->getParts($context);
		if (! $parts)
		{
			return true;
		}
		$context = $parts[0] . '.' . $parts[1];

		JLoader::import('joomla.application.component.model');
		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_dpfields/models', 'DPFieldsModel');
		$model = JModelLegacy::getInstance('Field', 'DPFieldsModel', array(
				'ignore_request' => true
		));
		$model->cleanupValues($context, $item->id);
		return true;
	}

	public function onExtensionAfterDelete ($context, $item)
	{
		return $this->onContentAfterDelete($context, $item);
	}

	public function onUserAfterDelete ($user, $succes, $msg)
	{
		if (! $this->isComponentAvailable())
		{
			return true;
		}

		$item = new stdClass();
		$item->id = $user['id'];
		return $this->onContentAfterDelete('com_users.user', $item);
	}

	public function onContentPrepareForm (JForm $form, $data)
	{
		if (! $this->isComponentAvailable())
		{
			return true;
		}

		$context = $form->getName();

		// Transform categories form name to a valid context
		if (strpos($context, 'com_categories.category') !== false)
		{
			$context = str_replace('com_categories.category', '', $context) . '.category';
		}

		// Extracting the component and section
		$parts = $this->getParts($context);
		if (! $parts)
		{
			return true;
		}

		// Getting the fields
		$fields = DPFieldsHelper::getFields($parts[0] . '.' . $parts[1], $data);
		if (! $fields)
		{
			return true;
		}

		// If we are on the save command we need the actual data
		$jformData = JFactory::getApplication()->input->get('jform', array(), 'array');
		if ($jformData && ! $data)
		{
			$data = $jformData;
		}

		if (is_array($data))
		{
			$data = (object) $data;
		}

		$component = $parts[0];
		$section = $parts[1];

		$assignedCatids = isset($data->catid) ? $data->catid : (isset($data->dpfieldscatid) ? $data->dpfieldscatid : null);
		if (! $assignedCatids && $form->getField('assigned_cat_ids'))
		{
			// Choose the first category available
			$xml = new DOMDocument();
			$xml->loadHTML($form->getField('assigned_cat_ids')
				->__get('input'));
			$options = $xml->getElementsByTagName('option');
			if ($firstChoice = $options->item(0))
			{
				$assignedCatids = $firstChoice->getAttribute('value');
				$data->dpfieldscatid = $assignedCatids;
			}
		}

		// If there is a catid field we need to reload the page when the catid
		// is changed
		if ($form->getField('catid') && $parts[0] != 'com_dpfields')
		{
			// The uri to submit to
			$uri = clone JUri::getInstance('index.php');

			// Removing the catid parameter from the actual url and set it as
			// return
			$returnUri = clone JUri::getInstance();
			$returnUri->setVar('catid', null);
			$uri->setVar('return', base64_encode($returnUri->toString()));

			// Setting the options
			$uri->setVar('option', 'com_dpfields');
			$uri->setVar('task', 'field.catchange');
			$uri->setVar('context', $parts[0] . '.' . $parts[1]);
			$uri->setVar('formcontrol', $form->getFormControl());
			$uri->setVar('view', null);
			$uri->setVar('layout', null);

			// Setting the onchange event to reload the page when the category
			// has changed
			$form->setFieldAttribute('catid', 'onchange', "categoryHasChanged(this);");
			JFactory::getDocument()->addScriptDeclaration(
					"function categoryHasChanged(element){
				var cat = jQuery(element);
				if (cat.val() == '" . $assignedCatids . "')return;
				jQuery('input[name=task]').val('field.catchange');
				element.form.action='" . $uri . "';
				element.form.submit();
			}
			jQuery( document ).ready(function() {
				var formControl = '#" . $form->getFormControl() . "_catid';
				if (!jQuery(formControl).val() != '" . $assignedCatids .
							 "'){jQuery(formControl).val('" . $assignedCatids . "');}
			});");
		}

		// Creating the dom
		$xml = new DOMDocument('1.0', 'UTF-8');
		$fieldsNode = $xml->appendChild(new DOMElement('form'))->appendChild(new DOMElement('fields'));
		$fieldsNode->setAttribute('name', 'params');
		$fieldsNode->setAttribute('addfieldpath', '/administrator/components/com_dpfields/models/types/fields');
		$fieldsNode->setAttribute('addrulepath', '/administrator/components/com_dpfields/models/types/rules');

		// Organizing the fields according to their category
		$fieldsPerCategory = array(
				0 => array()
		);
		foreach ($fields as $field)
		{
			if (! key_exists($field->catid, $fieldsPerCategory))
			{
				$fieldsPerCategory[$field->catid] = array();
			}
			$fieldsPerCategory[$field->catid][] = $field;
		}

		// Looping trough the categories
		foreach ($fieldsPerCategory as $catid => $catFields)
		{
			if (! $catFields)
			{
				continue;
			}

			// Defining the field set
			$fieldset = $fieldsNode->appendChild(new DOMElement('fieldset'));
			$fieldset->setAttribute('name', 'dpfields-' . $catid);
			$fieldset->setAttribute('addfieldpath', '/administrator/components/' . $component . '/models/fields');
			$fieldset->setAttribute('addrulepath', '/administrator/components/' . $component . '/models/rules');

			$label = '';
			$description = '';
			if ($catid > 0)
			{
				// JCategories can't handle com_content with a section, going
				// directly to the table
				$category = JTable::getInstance('Category');
				$category->load($catid);
				if ($category->id)
				{
					$label = $category->title;
					$description = $category->description;
				}
			}

			if (! $label || ! $description)
			{
				$lang = JFactory::getLanguage();

				if (! $label)
				{
					$key = strtoupper($component . '_FIELDS_' . $section . '_LABEL');
					if (! $lang->hasKey($key))
					{
						$key = 'PLG_SYSTEM_DPFIELDS_FIELDS';
					}
					$label = JText::_($key);
				}

				if (! $description)
				{
					$key = strtoupper($component . '_FIELDS_' . $section . '_DESC');
					if ($lang->hasKey($key))
					{
						$description = JText::_($key);
					}
				}
			}

			$fieldset->setAttribute('label', $label);
			$fieldset->setAttribute('description', $description);

			// Looping trough the fields for that context
			foreach ($catFields as $field)
			{
				// Creating the XML form data
				$type = DPFieldsHelper::loadTypeObject($field->type, $field->context);
				if ($type === false)
				{
					continue;
				}
				try
				{
					// Rendering the type
					$node = $type->appendXMLFieldTag($field, $fieldset, $form);

					// If the field belongs to a assigned_cat_ids but the
					// assigned_cat_ids in the data is not known, set the
					// required
					// flag to false on any circumstance
					if (! $assignedCatids && $field->assigned_cat_ids)
					{
						$node->setAttribute('required', 'false');
					}
				}
				catch (Exception $e)
				{
					JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
				}
			}
		}
		// Loading the XML fields string into the form
		$form->load($xml->saveXML());

		$model = JModelLegacy::getInstance('Field', 'DPFieldsModel', array(
				'ignore_request' => true
		));
		// Looping trough the fields again to set the value
		if (isset($data->id) && $data->id)
		{
			foreach ($fields as $field)
			{
				$value = $model->getFieldValue($field->id, $field->context, $data->id);
				if ($value === null)
				{
					continue;
				}
				// Setting the value on the field
				$form->setValue($field->alias, 'params', $value);
			}
		}

		return true;
	}

	public function onContentPrepareData ($context, $data)
	{
		if (! $this->isComponentAvailable())
		{
			return;
		}

		$parts = $this->getParts($context);
		if (! $parts)
		{
			return;
		}

		if (isset($data->params) && $data->params instanceof Registry)
		{
			$data->params = $data->params->toArray();
		}
	}

	public function onContentAfterTitle ($context, $item, $params, $limitstart = 0)
	{
		return $this->display($context, $item, $params, 1);
	}

	public function onContentBeforeDisplay ($context, $item, $params, $limitstart = 0)
	{
		return $this->display($context, $item, $params, 2);
	}

	public function onContentAfterDisplay ($context, $item, $params, $limitstart = 0)
	{
		return $this->display($context, $item, $params, 3);
	}

	private function display ($context, $item, $params, $displayType)
	{
		if (! $this->isComponentAvailable())
		{
			return '';
		}

		$parts = $this->getParts($context);
		if (! $parts)
		{
			return '';
		}
		$context = $parts[0] . '.' . $parts[1];

		// Check if we should be hidden
		foreach (DPFieldsHelper::getFields($context, $item) as $field)
		{
			if ($field->alias == self::DEFAULT_HIDE_ALIAS && $field->value)
			{
				return '';
			}
		}

		if (is_string($params))
		{
			$params = new Registry($params);
		}

		$fields = DPFieldsHelper::getFields($context, $item, true);
		if ($fields)
		{
			foreach ($fields as $key => $field)
			{
				$fieldDisplayType = $field->params->get('display', '-1');
				if ($fieldDisplayType == '-1')
				{
					$fieldDisplayType = $this->params->get('display', '2');
				}
				if ($fieldDisplayType == $displayType)
				{
					continue;
				}
				unset($fields[$key]);
			}
		}

		if ($fields)
		{
			return DPFieldsHelper::render($context, 'fields.render',
					array(
							'item' => $item,
							'context' => $context,
							'fields' => $fields,
							'container' => $params->get('dpfields-container'),
							'container-class' => $params->get('dpfields-container-class')
					));
		}
		return '';
	}

	public function onContentPrepare ($context, $item)
	{
		if (! $this->isComponentAvailable())
		{
			return;
		}

		$parts = $this->getParts($context);
		if (! $parts)
		{
			return;
		}

		$fields = DPFieldsHelper::getFields($parts[0] . '.' . $parts[1], $item, true);

		// Adding the fields to the object
		$item->dpfields = array();
		foreach ($fields as $key => $field)
		{
			// Hide the field which is for operational purposes only
			if ($field->alias == self::DEFAULT_HIDE_ALIAS)
			{
				unset($fields[$key]);
				continue;
			}

			$item->dpfields[$field->id] = $field;
		}

		// If we don't meet all the requirements return
		if (! isset($item->id) || ! $item->id || ! isset($item->text) || ! $item->text || ! JString::strpos($item->text, 'dpfields') !== false ||
				 ! $this->params->get('prepare_content', '1'))
		{
			return true;
		}

		// Count how many times we need to process the fields
		$count = substr_count($item->text, '{{#dpfields');
		for ($i = 0; $i < $count; $i ++)
		{
			// Check for parameters
			preg_match('/{{#dpfields\s*.*?}}/i', $item->text, $starts, PREG_OFFSET_CAPTURE);
			preg_match('/{{\/dpfields}}/i', $item->text, $ends, PREG_OFFSET_CAPTURE);

			// Extract the parameters
			$start = $starts[0][1] + strlen($starts[0][0]);
			$end = $ends[0][1];
			$params = explode(' ', str_replace(array(
					'{{#dpfields',
					'}}'
			), '', $starts[0][0]));

			// Clone the fields because we are manipulating the array and need
			// it on the next iteration again
			$contextFields = array_merge(array(), $fields);

			// Loop trough the params and set them on the model
			foreach ($params as $string)
			{
				$string = trim($string);
				if (! $string)
				{
					continue;
				}

				$paramKey = null;
				$paramValue = null;
				$parts = explode('=', $string);
				if (count($parts) > 0)
				{
					$paramKey = $parts[0];
				}
				if (count($parts) > 1)
				{
					$paramValue = $parts[1];
				}

				if ($paramKey == 'id')
				{
					$paramValue = explode(',', $paramValue);
					JArrayHelper::toInteger($paramValue);
					foreach ($contextFields as $key => $field)
					{
						if (! in_array($field->id, $paramValue))
						{
							unset($contextFields[$key]);
						}
					}
				}
				if ($paramKey == 'alias')
				{
					$paramValue = explode(',', $paramValue);
					foreach ($contextFields as $key => $field)
					{
						if (! in_array($field->alias, $paramValue))
						{
							unset($contextFields[$key]);
						}
					}
				}
			}

			// Mustache can't handle arrays with unsets properly
			$contextFields = array_values($contextFields);

			try
			{
				// Load the mustache engine
				JLoader::import('components.com_dpfields.libraries.Mustache.Autoloader', JPATH_ADMINISTRATOR);
				Mustache_Autoloader::register();

				$m = new Mustache_Engine();
				$output = $m->render('{{#dpfields}}' . substr($item->text, $start, $end - $start) . '{{/dpfields}}',
						array(
								'dpfields' => $contextFields
						));

				// Set the output on the item
				$item->text = substr_replace($item->text, $output, $starts[0][1], $end + 13 - $starts[0][1]);
			}
			catch (Exception $e)
			{
				JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
			}
		}
		return true;
	}

	public function onAfterCleanModuleList ($modules)
	{
		if (! $this->isComponentAvailable())
		{
			return true;
		}

		foreach ($modules as $module)
		{
			$module->text = $module->content;
			$this->onContentPrepare('com_modules.module', $module);
			$module->content = $module->text;
			unset($module->text);
		}
		return true;
	}

	public function onPrepareFinderContent ($item)
	{
		if (! $this->isComponentAvailable())
		{
			return true;
		}

		$section = strtolower($item->layout);
		$tax = $item->getTaxonomy('Type');
		if ($tax)
		{
			foreach ($tax as $context => $value)
			{
				// This is only a guess, needs to be improved
				$component = strtolower($context);
				if (strpos($context, 'com_') !== 0)
				{
					$component = 'com_' . $component;
				}

				// Transofrm com_article to com_content
				if ($component == 'com_article')
				{
					$component = 'com_content';
				}

				// Create a dummy object with the required fields
				$tmp = new stdClass();
				$tmp->id = $item->__get('id');
				if ($item->__get('catid'))
				{
					$tmp->catid = $item->__get('catid');
				}

				// Getting the fields for the constructed context
				$fields = DPFieldsHelper::getFields($component . '.' . $section, $tmp, true);
				if (is_array($fields))
				{
					foreach ($fields as $field)
					{
						// Adding the instructions how to handle the text
						$item->addInstruction(FinderIndexer::TEXT_CONTEXT, $field->alias);

						// Adding the field value as a field
						$item->{$field->alias} = $field->value;
					}
				}
			}
		}
		return true;
	}

	private function getParts ($context)
	{
		if (! $this->isComponentAvailable())
		{
			return null;
		}

		$parts = DPFieldsHelper::extract($context);
		if (! $parts)
		{
			return null;
		}

		// Check for supported contexts
		$component = $parts[0];
		if (key_exists($component, $this->supportedContexts))
		{
			$section = $this->supportedContexts[$component];

			// All sections separated with a , after the first ones are aliases
			if (strpos($section, ',') !== false)
			{
				$sectionParts = explode(',', $section);
				if (in_array($parts[1], $sectionParts))
				{
					$parts[1] = $sectionParts[0];
				}
			}
		}
		else if ($parts[1] == 'form')
		{
			// The context is not from a known one, we need to do a lookup
			$db = JFactory::getDbo();
			$db->setQuery('select context from #__dpfields_fields where context like ' . $db->q($parts[0] . '.%') . ' group by context');
			$tmp = $db->loadObjectList();

			if (count($tmp) == 1)
			{
				$parts = DPFieldsHelper::extract($tmp[0]->context);
				if (count($parts) < 2)
				{
					return null;
				}
			}
		}

		return $parts;
	}

	private function isComponentAvailable ()
	{
		return JFile::exists(JPATH_ADMINISTRATOR . '/components/com_dpfields/helpers/dpfields.php');
	}
}
