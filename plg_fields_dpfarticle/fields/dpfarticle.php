<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JLoader::import('components.com_content.models.fields.modal.article', JPATH_ADMINISTRATOR);

class JFormFieldDPFArticle extends JFormFieldModal_Article
{
	public $type = 'DPFArticle';
}
