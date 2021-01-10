<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Basic\Form;
use CCL\Content\Element\Component\Icon;
use DPFields\Helper\DPFieldsHelper;

$root = $this->root->addChild(new Container('filters'));

// Add the toggle icon
$title = JText::_('COM_DPFIELDS_VIEW_CATEGORY_FILTER_TOGGLE_TEXT');
$t     = $root->addChild(new Container('toggle'));
$t->addChild(new Icon('up', Icon::UP, array(), array('data-direction' => 'up', 'title' => $title)));
$t->addChild(new Icon('down', Icon::DOWN, array(), array('data-direction' => 'down', 'title' => $title)));

JFactory::getDocument()->addScriptDeclaration("
jQuery(document).ready(function() {
	// Toggle the filter
	var selector = '#" . $t->getId() . "';
	jQuery(selector).bind('click', function(e) {
		jQuery('#dp-category-filters-form').slideToggle('slow', function() {
			if (!jQuery('#dp-category-filters-form').is(':visible')) {
				jQuery(selector + ' i[data-direction=\"up\"]').hide();
				jQuery(selector + ' i[data-direction=\"down\"]').show();
			} else {
				jQuery(selector + ' i[data-direction=\"up\"]').show();
				jQuery(selector + ' i[data-direction=\"down\"]').hide();
			}
		});
	});
	
	if (jQuery('#dp-category-filters-form-container-fields-filters .control-group').length > 1){
		jQuery('#dp-category-filters-form').show();	
		jQuery(selector + ' i[data-direction=\"up\"]').show();
		jQuery(selector + ' i[data-direction=\"down\"]').hide();
	}
});");

// If we are in a model
$tmpl = $this->input->getCmd('tmpl');
if ($tmpl) {
	$tmpl = '&tmpl=' . $tmpl;
}

/** @var Form $form * */
$form = $root->addChild(
	new Form(
		'form',
		JRoute::_('index.php?option=com_dpfields&view=category&Itemid=' . $this->input->getInt('Itemid') . $tmpl),
		'adminForm',
		'POST',
		array('form-validate')
	)
);

// Add the submit button
JLayoutHelper::render(
	'content.button',
	array(
		'type'    => Icon::SEARCH,
		'root'    => $form,
		'text'    => 'COM_DPFIELDS_VIEW_CATEGORY_APPLY_FILTER_TEXT',
		'onclick' => "this.form.submit();"
	)
);

// Some hidden input fields for ordering
$form->addChild(new Form\Input('order', 'hidden', 'filter_order'));
$form->addChild(new Form\Input('dir', 'hidden', 'filter_order_Dir'));
$form->addChild(new Form\Input('limit', 'hidden', 'limitstart'));
$form->addChild(new Form\Input('task', 'hidden', 'task'));

// Render the form
DPFieldsHelper::renderLayout('content.form', array('root' => $form, 'jform' => $this->filterForm, 'flat' => true));
