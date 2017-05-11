<?php

namespace CCL\Content\Element\Component;

use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Basic\Link;
use CCL\Content\Element\Basic\ListContainer;
use CCL\Content\Element\Basic\ListItem;

/**
 * A TabContainer representation.
 */
class TabContainer extends Container
{

	private $tabLinks = null;

	private $tabs = null;

	/**
	 * Adds the given tabs to the internal tabs container and returns it for chaining.
	 *
	 * @param Tab $tab
	 *
	 * @return Tab
	 */
	public function addTab(Tab $tab)
	{
		$this->getTabs()->addChild($tab);

		$li = $this->getTabLinks()->addChild(new ListItem('tab-' . (count($this->getTabLinks()->getChildren()) + 1)));
		$li->addChild(new Link('link', '#' . $tab->getId()))->setContent($tab->getTitle());

		return $tab;
	}

	/**
	 * Returns the container for the tab links.
	 *
	 * @return \CCL\Content\Element\Basic\ListContainer
	 */
	public function getTabLinks()
	{
		if ($this->tabLinks === null) {
			$this->tabLinks = new ListContainer('links', ListContainer::UNORDERED);
			$this->addChild($this->tabLinks);
		}

		return $this->tabLinks;
	}

	/**
	 * Returns the container for the tabs.
	 *
	 * @return \CCL\Content\Element\Basic\Container
	 */
	public function getTabs()
	{
		if ($this->tabs === null) {
			$this->getTabLinks();

			$this->tabs = new Container('tabs');
			$this->addChild($this->tabs);
		}

		return $this->tabs;
	}
}
