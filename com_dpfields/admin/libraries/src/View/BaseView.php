<?php
/**
 * @package    DPFields
 * @copyright  (C) 2017 Digital Peak GmbH. <https://www.digital-peak.com>
 * @license    http://www.gnu.org/licenses/gpl.html GNU/GPL
 */
namespace DPFields\View;

defined('_JEXEC') or die();

use DPFields\Helper\DPFieldsHelper;
use Joomla\Registry\Registry;
use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Basic\Heading;

class BaseView extends \JViewLegacy
{
	protected $state;
	protected $params;
	protected $input = null;
	protected $root = null;

	/**
	 * @var \JApplicationCms
	 */
	protected $app = null;

	/**
	 * @var \JUser
	 */
	protected $user = null;

	public function display($tpl = null)
	{
		$this->app = \JFactory::getApplication();
		$this->input = $this->app->input;
		$this->user = \JFactory::getUser();

		$state = $this->get('State');

		if ($state === null) {
			$state = new Registry();
		}

		$tmp = clone $state->get('params', new Registry());
		if (method_exists($this->app, 'getParams')) {
			$tmp->merge($this->app->getParams('com_dpfields'));
		}

		$this->state = $state;
		$this->params = $tmp;

		$this->root = new Container('dp-' . $this->getName(), array(), array('ccl-prefix' => 'dp-' . $this->getName() . '-'));

		// Add page heading when requested
		if ($this->params->get('show_page_heading')) {
			$this->root->addChild(new Heading('page-heading', 1))->setContent($this->params->get('page_heading'));
		}

		$this->init();

		if (count($errors = $this->get('Errors'))) {
			\JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		if ($this->app->isClient('site')) {
			$this->prepareDocument();
		} else {
			if ($this->getLayout() !== 'modal') {
				DPFieldsHelper::addSubmenu($this->getName());
			}
			$this->addToolbar();

			// Only render the sidebar when we are not editing a form
			if (!($this->getModel() instanceof \JModelAdmin)) {
				$this->sidebar = \JHtmlSidebar::render();
				$this->filterForm = $this->get('FilterForm');
				$this->activeFilters = $this->get('ActiveFilters');
			}
		}

		parent::display($tpl);
	}

	protected function prepareDocument()
	{
		$menus = $this->app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', \JText::_('COM_DPFIELDS_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $this->app->getCfg('sitename');
		} else {
			if ($this->app->getCfg('sitename_pagetitles', 0) == 1) {
				$title = \JText::sprintf('JPAGETITLE', $this->app->getCfg('sitename'), $title);
			} else {
				if ($this->app->getCfg('sitename_pagetitles', 0) == 2) {
					$title = \JText::sprintf('JPAGETITLE', $title, $this->app->getCfg('sitename'));
				}
			}
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description')) {
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots')) {
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		// Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));
	}


	protected function addToolbar()
	{
	}

	protected function init()
	{
	}
}
