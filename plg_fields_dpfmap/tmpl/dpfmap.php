<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

// Add the stylesheet
JHtml::_('stylesheet', 'plg_fields_dpfmap/dpfmap.css', array('version' => 'auto', 'relative' => true));

// Compile the url
$url = 'https://www.google.com/maps/embed/v1/' . $fieldParams->get('mode', 'place');
$url .= '?key=' . $fieldParams->get('api_key');
$url .= '&q=' . urlencode($field->value);
$url .= '&maptype=' . $fieldParams->get('type', 'roadmap');
$url .= '&zoom=' . $fieldParams->get('zoom', 8);
?>
<div class="dpfmap-responsive-map" style="height: <?php echo (int)$fieldParams->get('height', 500); ?>px">
    <iframe
            frameborder="0" style="border:0"
            src="<?php echo $url; ?>"
            allowfullscreen>
    </iframe>
</div>
