<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

JFormHelper::loadFieldClass('media');

class JFormFieldDPFMedia extends JFormFieldMedia
{
	public $type = 'DPFMedia';
}
