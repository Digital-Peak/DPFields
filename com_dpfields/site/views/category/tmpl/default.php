<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

// Load the stylesheet
JHtml::_('stylesheet', 'com_dpfields/views/category/default.css', array(), true);

// Load the templates
$this->loadTemplate('filters');
$this->loadTemplate('category');
$this->loadTemplate('entities');

// Render the element
echo \DPFields\Helper\DPFieldsHelper::renderElement($this->root, $this->params);
