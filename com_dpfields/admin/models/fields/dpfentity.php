<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JFormHelper::loadFieldClass('list');

class JFormFieldDPFEntity extends JFormFieldList
{
	public $type = 'DPFEntity';

	protected function getOptions()
	{
		\JLoader::import('components.com_dpfields.models.contenttypes', JPATH_ADMINISTRATOR);
		\JLoader::import('components.com_dpfields.models.entities', JPATH_ADMINISTRATOR);

		$model = new \DPFieldsModelContentTypes();
		$contentTypes = $model->getItems();

		$options = array();
		foreach ($contentTypes as $contentType) {
			$context = 'com_dpfields.' . $contentType->name;

			$model = new DPFieldsModelEntities(array('ignore_request' => true));
			$model->setState('filter.published', 1);
			$model->setState('filter.context', 'com_dpfields.' . $contentType->name);

			foreach ($model->getItems() as $entity) {
				$options[] = JHtml::_('select.option', $entity->id, JText::_($entity->title) . ' [' . $contentType->name . ']');
			}
		}

		// Merge any additional options in the XML definition.
		return array_merge(parent::getOptions(), $options);
	}
}
