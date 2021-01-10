<?php
/**
 * @package    DPFields
 * @copyright  (C) 2015 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_dpfields.vendor.autoload', JPATH_ADMINISTRATOR);

JLoader::register('DPFieldsHelperRoute', JPATH_SITE . '/components/com_dpfields/helpers/route.php');
JLoader::register('DPFieldsHelperQuery', JPATH_SITE . '/components/com_dpfields/helpers/query.php');

JFactory::getLanguage()->load('com_dpfields', JPATH_ADMINISTRATOR . '/components/com_dpfields');

$controller = JControllerLegacy::getInstance('DPFields');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
