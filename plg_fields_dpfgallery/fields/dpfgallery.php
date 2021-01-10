<?php
/**
 * @package    DPFields
 * @copyright  (C) 2015 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JFormHelper::loadFieldClass('folderlist');

class JFormFieldDPFGallery extends JFormFieldFolderList
{
	public $type = 'DPFGallery';
}
