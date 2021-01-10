<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JFormHelper::loadFieldClass('list');

class JFormFieldDPFCategory extends JFormFieldList
{
	public $type = 'DPFCategory';

	protected function getOptions()
	{
		$options = array();

		\JLoader::import('components.com_dpfields.models.contenttypes', JPATH_ADMINISTRATOR);
		$model = new \DPFieldsModelContentTypes();
		$contentTypes = $model->getItems();

		$published = (string)$this->element['published'];
		$language = (string)$this->element['language'];

		// Load the category options for a given extension.
		foreach ($contentTypes as $contentType) {
			$extension = 'com_dpfields.' . $contentType->name;

			// Filter over published state or not depending upon if it is present.
			$filters = array();
			if ($published) {
				$filters['filter.published'] = explode(',', $published);
			}

			// Filter over language depending upon if it is present.
			if ($language) {
				$filters['filter.language'] = explode(',', $language);
			}

			if ($filters === array()) {
				$cats = JHtml::_('category.options', $extension);
			} else {
				$cats = JHtml::_('category.options', $extension, $filters);
			}

			// Verify permissions.  If the action attribute is set, then we scan the options.
			if ((string)$this->element['action']) {
				// Get the current user object.
				$user = JFactory::getUser();

				foreach ($cats as $i => $option) {
					/*
					 * To take save or create in a category you need to have create rights for that category
					 * unless the item is already in that category.
					 * Unset the option if the user isn't authorised for it. In this field assets are always categories.
					 */
					if ($user->authorise('core.create', $extension . '.category.' . $option->value) != true) {
						unset($cats[$i]);
					}
				}
			}

			$options = array_merge($options, $cats);
		}

		if (isset($this->element['show_root'])) {
			array_unshift($options, JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT')));
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
