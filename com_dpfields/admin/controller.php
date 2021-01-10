<?php
/**
 * @package    DPFields
 * @copyright  (C) 2015 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class DPFieldsController extends JControllerLegacy
{
	protected $default_view = 'contenttypes';

	public function display($cachable = false, $urlparams = array())
	{
		$view = $this->input->get('view', 'contenttypes');
		$layout = $this->input->get('layout', 'default');
		$id = $this->input->getInt('id');

		// Check for edit form.
		if ($view == 'contenttype' && $layout == 'edit' && !$this->checkEditId('com_dpfields.edit.contenttype', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_dpfields&view=contenttypes', false));

			return false;
		}

		// Check for edit form.
		if ($view == 'entity' && $layout == 'edit' && !$this->checkEditId('com_dpfields.edit.entity', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_dpfields&view=entities', false));

			return false;
		}

		return parent::display();
	}
}
