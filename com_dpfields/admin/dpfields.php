<?php
/**
 * @package    DPFields
 * @copyright  (C) 2015 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

if (!JFactory::getUser()->authorise('core.manage', 'com_dpfields')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::import('components.com_dpfields.vendor.autoload', JPATH_ADMINISTRATOR);

$input = JFactory::getApplication()->input;

if (strpos($input->get('task'), '.reload')) {
	$input->set('task', 'entity.reload');
}

$controller = JControllerLegacy::getInstance('DPFields');
$controller->execute($input->get('task'));
$controller->redirect();
