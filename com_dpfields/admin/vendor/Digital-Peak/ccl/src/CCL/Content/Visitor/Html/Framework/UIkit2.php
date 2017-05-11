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
use CCL\Content\Element\Component\Grid;

/**
 * The Uikit 2 framework visitor.
 */
class UIkit2 extends AbstractElementVisitor
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
		Alert::DANGER  => 'danger'
	];

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitAlert()
	 */
	public function visitAlert(Alert $alert)
	{
		$alert->addClass('uk-alert', true);
		$alert->addClass('uk-alert-' . $this->alertTypes[$alert->getType()], true);
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitBadge()
	 */
	public function visitBadge(Badge $badge)
	{
		$badge->addClass('uk-badge', true);
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitButton()
	 */
	public function visitButton(Button $button)
	{
		$button->addClass('uk-button', true);
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitDescriptionListHorizontal()
	 */
	public function visitDescriptionListHorizontal(DescriptionListHorizontal $descriptionListHorizontal)
	{
		$descriptionListHorizontal->addClass('uk-description-list-horizontal', true);
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitForm()
	 */
	public function visitForm(Form $form)
	{
		$form->addClass('uk-form', true);
		$form->addClass('uk-form-horizontal', true);
	}

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitFormLabel()
	 */
	public function visitFormLabel(\CCL\Content\Element\Basic\Form\Label $formLabel)
	{
		$formLabel->addClass('uk-form-label', true);
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\Basic\ElementVisitorInterface::visitGridColumn()
	 */
	public function visitGridColumn(Column $gridColumn)
	{
		$width = (10 / 100) * $gridColumn->getWidth();
		$width = round($width);

		if ($width < 1) {
			$width = 1;
		}
		if ($width > 10) {
			$width = 10;
		}

		$gridColumn->addClass('uk-width-' . $width . '-10', true);
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGridRow()
	 */
	public function visitGridRow(Row $gridRow)
	{
		$gridRow->addClass('uk-grid', true);
		$gridRow->addClass('uk-grid-collapse', true);
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTabContainer()
	 */
	public function visitTabContainer(TabContainer $tabContainer)
	{
		// Set up the tab links
		$tabLinks = $tabContainer->getTabLinks();
		$tabLinks->addClass('uk-tab', true);
		$tabLinks->addAttribute('data-uk-tab', '{connect:"#' . $tabContainer->getTabs()->getId() . '"}');

		// Set the first one as active
		foreach ($tabLinks->getChildren() as $index => $link) {
			if ($index == 0) {
				$link->addClass('uk-active', true);
				break;
			}
		}

		// Set up the tab content
		$tabContainer->getTabs()->addClass('uk-switcher', true);
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitTable()
	 */
	public function visitTable(Table $table)
	{
		$table->addClass('uk-table', true);
		$table->addClass('uk-table-stripped', true);
	}
}
