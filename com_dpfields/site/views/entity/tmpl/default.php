<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

// Load the stylesheet
JHtml::_('stylesheet', 'com_dpfields/views/entity/default.css', array(), true);

// Load the sub templates
$this->loadTemplate('header');
$this->loadTemplate('title');
$this->loadTemplate('fields');

echo \DPFields\Helper\DPFieldsHelper::renderElement($this->root, $this->params);
