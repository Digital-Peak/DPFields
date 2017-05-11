<?php

namespace CCL\Content\Visitor\Html\Framework;

use CCL\Content\Element\Component\Alert;
use CCL\Content\Element\Component\Badge;
use CCL\Content\Element\Basic\Button;
use CCL\Content\Element\Basic\DescriptionListHorizontal;
use CCL\Content\Element\Basic\Form;
use CCL\Content\Element\Component\Grid\Column;
use CCL\Content\Element\Component\Grid\Row;
use CCL\Content\Element\Basic\Link;
use CCL\Content\Element\Component\Tab;
use CCL\Content\Element\Component\TabContainer;
use CCL\Content\Element\Basic\Table;
use CCL\Content\Visitor\AbstractElementVisitor;

/**
 * The Bootstrap 2 framework visitor.
 */
class BS2 extends AbstractElementVisitor
{

	/**
	 * The alert mappings.
	 *
	 * @var array
	 */
	protected $alertTypes = [
		Alert::INFO    => 'info',
		Alert::SUCCESS => 'success',
		Alert::WARNING => 'warning',
		Alert::DANGER  => 'error'
	];

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitAlert()
	 */
	public function visitAlert(Alert $alert)
	{
		$alert->addClass('alert', true);
		$alert->addClass('alert-' . $this->alertTypes[$alert->getType()], true);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitBadge()
	 */
	public function visitBadge(Badge $badge)
	{
		$badge->addClass('badge', true);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitButton()
	 */
	public function visitButton(Button $button)
	{
		$button->addClass('btn', true);
		$button->addClass('btn-default', true);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitDescriptionListHorizontal()
	 */
	public function visitDescriptionListHorizontal(DescriptionListHorizontal $descriptionListHorizontal)
	{
		$descriptionListHorizontal->addClass('dl-horizontal', true);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitForm()
	 */
	public function visitForm(Form $form)
	{
		$form->addClass('form-horizontal', true);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGridColumn()
	 */
	public function visitGridColumn(Column $gridColumn)
	{
		$gridColumn->addClass('span' . $this->calculateWidth($gridColumn->getWidth()), true);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGridRow()
	 */
	public function visitGridRow(Row $gridRow)
	{
		$gridRow->addClass('row-fluid', true);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitListContainer()
	 */
	public function visitListContainer(\CCL\Content\Element\Basic\ListContainer $listContainer)
	{
		if (!$listContainer->getParent() instanceof TabContainer) {
			$listContainer->addClass('list-striped', true);
		}
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTabContainer()
	 */
	public function visitTabContainer(TabContainer $tabContainer)
	{
		// Set up the tab links
		$tabLinks = $tabContainer->getTabLinks();
		$tabLinks->addClass('nav', true);
		$tabLinks->addClass('nav-tabs', true);


		// Set the first one as active and add the toggle attribute
		foreach ($tabLinks->getChildren() as $index => $link) {
			if ($index == 0) {
				$link->addClass('active', true);
			}
			$link->getChildren()[0]->addAttribute('data-toggle', 'tab');
		}

		// Set up the tab content
		$tabContainer->getTabs()->addClass('tab-content', true);
		foreach ($tabContainer->getTabs()->getChildren() as $index => $tab) {
			if ($index == 0) {
				$tab->addClass('active', true);
			}
			$tab->addClass('tab-pane', true);
		}
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTable()
	 */
	public function visitTable(Table $table)
	{
		$table->addClass('table', true);
		$table->addClass('table-striped', true);
	}

	/**
	 * Calculates the width.
	 *
	 * @param number $width
	 * @param number $maxWidth
	 *
	 * @return number
	 */
	protected function calculateWidth($width, $maxWidth = 12)
	{
		$newWidth = ($maxWidth / 100) * $width;
		$newWidth = round($newWidth);

		if ($newWidth < 1) {
			$newWidth = 1;
		}
		if ($newWidth > $maxWidth) {
			$newWidth = $maxWidth;
		}

		return $newWidth;
	}
}
