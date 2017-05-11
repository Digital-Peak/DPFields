<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

if (!JFactory::getUser()->authorise('core.manage', 'com_dpfields')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::import('components.com_dpfields.vendor.autoload', JPATH_ADMINISTRATOR);

$controller = JControllerLegacy::getInstance('DPFields');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
