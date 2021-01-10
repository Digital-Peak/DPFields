<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

use Joomla\Utilities\ArrayHelper;

class PlgContentDPFields extends JPlugin
{
	protected $autoloadLanguage = true;

	public function onContentPrepare($context, $item)
	{
		// If we don't meet all the requirements return
		if (!isset($item->id) || !$item->id || !isset($item->text) || !$item->text || empty($item->jcfields)) {
			return true;
		}

		// Count how many times we need to process the fields
		$count = substr_count($item->text, '{{#dpfields');
		for ($i = 0; $i < $count; $i++) {
			// Check for parameters
			preg_match('/{{#dpfields\s*.*?}}/i', $item->text, $starts, PREG_OFFSET_CAPTURE);
			preg_match('/{{\/dpfields}}/i', $item->text, $ends, PREG_OFFSET_CAPTURE);

			// Extract the parameters
			$start = $starts[0][1] + strlen($starts[0][0]);
			$end = $ends[0][1];
			$params = explode(' ', str_replace(array('{{#dpfields', '}}'), '', $starts[0][0]));

			// Clone the fields because we are manipulating the array and need
			// it on the next iteration again
			$contextFields = array_merge(array(), $item->jcfields);

			// Loop trough the params and set them on the model
			foreach ($params as $string) {
				$string = trim($string);
				if (!$string) {
					continue;
				}

				$paramKey = null;
				$paramValue = null;
				$parts = explode('=', $string);
				if (count($parts) > 0) {
					$paramKey = $parts[0];
				}
				if (count($parts) > 1) {
					$paramValue = $parts[1];
				}

				if ($paramKey == 'id') {
					$paramValue = explode(',', $paramValue);
					ArrayHelper::toInteger($paramValue);
					foreach ($contextFields as $key => $field) {
						if (!in_array($field->id, $paramValue)) {
							unset($contextFields[$key]);
						}
					}
				}
				if ($paramKey == 'alias' || $paramKey == 'name') {
					$paramValue = explode(',', $paramValue);
					foreach ($contextFields as $key => $field) {
						if (!in_array($field->name, $paramValue)) {
							unset($contextFields[$key]);
						}
					}
				}
			}

			// Mustache can't handle arrays with unsets properly
			$contextFields = array_values($contextFields);

			try {
				// Load the mustache engine
				JLoader::import('components.com_dpfields.vendor.autoload', JPATH_ADMINISTRATOR);
				Mustache_Autoloader::register();

				$m = new Mustache_Engine();
				$output = $m->render(
					'{{#dpfields}}' . substr($item->text, $start, $end - $start) . '{{/dpfields}}',
					array('dpfields' => $contextFields)
				);

				// Set the output on the item
				$item->text = substr_replace($item->text, $output, $starts[0][1], $end + 13 - $starts[0][1]);
			} catch (Exception $e) {
				JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
			}
		}
		return true;
	}
}
