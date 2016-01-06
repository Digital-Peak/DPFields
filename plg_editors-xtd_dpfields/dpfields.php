<?php
/**
 * @package    DPFields
 * @author     Digital Peak http://www.digital-peak.com
 * @copyright  Copyright (C) 2015 - 2016 Digital Peak. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

if (! JFile::exists(JPATH_ADMINISTRATOR . '/components/com_dpfields/helpers/dpfields.php'))
{
	return;
}

JLoader::register('DPFieldsHelper', JPATH_ADMINISTRATOR . '/components/com_dpfields/helpers/dpfields.php');

class plgButtondpfields extends JPlugin
{

	protected $autoloadLanguage = true;

	public function onDisplay ($name)
	{
		$input = JFactory::getApplication()->input;
		$context = $input->getCmd('option') . '.' . $input->getCmd('view');
		/*
		 * Javascript to insert the link
		 * View element calls jSelectArticle when an article is clicked
		 * jSelectArticle creates the link tag, sends it to the editor,
		 * and closes the select frame.
		 */
		$js = "
		function jSelectField(id, title, catid)
		{
			var tag = '{{#dpfields id=' + id + '}}{{value}}{{/dpfields}}';
			jInsertEditorText(tag, '" . $name . "');
			jModalClose();
		}";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration($js);

		/*
		 * Use the built-in element view to select the article.
		 * Currently uses blank class.
		 */
		$link = 'index.php?option=com_dpfields&amp;view=fields&amp;layout=modal&amp;tmpl=component&amp;' . JSession::getFormToken() . '=1&context=' .
				 $context;

		$button = new JObject();
		$button->modal = true;
		$button->class = 'btn';
		$button->link = $link;
		$button->text = JText::_('PLG_EDITORS-XTD_DPFIELDS_BUTTON_TEXT');
		$button->name = 'tree';
		$button->options = "{handler: 'iframe', size: {x: 800, y: 500}}";

		return $button;
	}
}
