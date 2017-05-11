<?php

namespace CCL\Content\Visitor\Html\Framework;

use CCL\Content\Element\Component\Grid\Column;
use CCL\Content\Element\Component\Grid\Row;
use CCL\Content\Element\Component\Alert;

/**
 * The Bootstrap 3 framework visitor.
 */
class BS3 extends BS2
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
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGridColumn()
	 */
	public function visitGridColumn(Column $gridColumn)
	{
		$gridColumn->addClass('col-md-' . $this->calculateWidth($gridColumn->getWidth()), true);
	}

	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\ElementVisitorInterface::visitGridRow()
	 */
	public function visitGridRow(Row $gridRow)
	{
		$gridRow->addClass('row', true);
	}
}
