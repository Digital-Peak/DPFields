<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2017 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_content.models.fields.modal.article', JPATH_ADMINISTRATOR);

class JFormFieldDPFArticle extends JFormFieldModal_Article
{
	public $type = 'DPFArticle';
}
