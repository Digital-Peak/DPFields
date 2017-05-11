<?php

namespace CCL\Content\Visitor\Html\IconStrategy;

use CCL\Content\Element\Component\Icon;
use CCL\Content\Visitor\AbstractElementVisitor;

/**
 * The Joomla icon strategy.
 */
class FontAwesome4 extends AbstractElementVisitor
{

	/**
	 * {@inheritdoc}
	 *
	 * @see \CCL\Content\Visitor\AbstractElementVisitorInterface::visitIcon()
	 */
	public function visitIcon(Icon $icon)
	{
		switch ($icon->getType()) {
			case Icon::CALENDAR:
				$icon->addClass('fa fa-calendar', true);
				break;
			case Icon::CANCEL:
				$icon->addClass('fa fa-ban', true);
				break;
			case Icon::DELETE:
				$icon->addClass('fa fa-trash', true);
				break;
			case Icon::DOWN:
				$icon->addClass('fa fa-caret-down', true);
				break;
			case Icon::DOWNLOAD:
				$icon->addClass('fa fa-download', true);
				break;
			case Icon::EDIT:
				$icon->addClass('fa fa-pencil', true);
				break;
			case Icon::FILE:
				$icon->addClass('fa fa-file', true);
				break;
			case Icon::INFO:
				$icon->addClass('fa fa-info', true);
				break;
			case Icon::MAIL:
				$icon->addClass('fa fa-envelope', true);
				break;
			case Icon::PLUS:
				$icon->addClass('fa fa-plus', true);
				break;
			case Icon::LOCATION:
				$icon->addClass('fa fa-map-marker', true);
				break;
			case Icon::LOCATION:
				$icon->addClass('fa fa-lock', true);
				break;
			case Icon::OK:
				$icon->addClass('fa fa-check', true);
				break;
			case Icon::PRINTING:
				$icon->addClass('fa fa-print', true);
				break;
			case Icon::SEARCH:
				$icon->addClass('fa fa-search', true);
				break;
			case Icon::SIGNUP:
				$icon->addClass('fa fa-sign-in', true);
				break;
			case Icon::UP:
				$icon->addClass('fa fa-caret-up', true);
				break;
			case Icon::USERS:
				$icon->addClass('fa fa-users', true);
				break;
		}
	}
}
