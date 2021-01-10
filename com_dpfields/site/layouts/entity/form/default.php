<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();


use CCL\Content\Element\Basic\Form;
use CCL\Content\Element\Basic\Element;
use CCL\Content\Element\Basic\Form\Input;

/**
 * Layout variables
 * -----------------
 * @var object $entity
 * @var object $form
 * @var object $user
 * @var object $input
 * @var object $params
 * @var string $returnPage
 **/
extract($displayData);

// Load the stylesheet
JHtml::_('stylesheet', 'com_dpfields/layouts/entity/form/default.css', array(), true);

// The form element
$root = new Form(
	'dp-entity-form',
	JRoute::_('index.php?option=com_dpfields&layout=edit&e_id=' . $entity->id . '&context=' . $input->get('context'), false),
	'adminForm',
	'POST',
	array('form-validate')
);

if ($app->isSite()) {
	$displayData['root'] = $root;

	// Load the header template
	\DPFields\Helper\DPFieldsHelper::renderLayout('entity.form.header', $displayData);
}

// Load the form from the layout
\DPFields\Helper\DPFieldsHelper::renderLayout(
	'content.form',
	array(
		'root'   => $root,
		'jform'  => $form,
		'return' => $returnPage
	)
);

// Render the element tree
echo \DPFields\Helper\DPFieldsHelper::renderElement($root, $params);
