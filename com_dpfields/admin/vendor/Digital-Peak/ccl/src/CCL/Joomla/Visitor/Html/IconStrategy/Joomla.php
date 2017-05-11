<?php

namespace CCL\Joomla\Visitor\Html\IconStrategy;

use CCL\Content\Element\Component\Icon;
use CCL\Content\Visitor\AbstractElementVisitor;

/**
 * The Joomla icon strategy.
 */
class Joomla extends AbstractElementVisitor
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
				$icon->addClass('icon-calendar', true);
				break;
			case Icon::CANCEL:
				$icon->addClass('icon-remove', true);
				break;
			case Icon::DELETE:
				$icon->addClass('icon-remove', true);
				break;
			case Icon::DOWN:
				$icon->addClass('icon-arrow-down', true);
				break;
			case Icon::DOWNLOAD:
				$icon->addClass('icon-download', true);
				break;
			case Icon::EDIT:
				$icon->addClass('icon-edit', true);
				break;
			case Icon::FILE:
				$icon->addClass('icon-file', true);
				break;
			case Icon::INFO:
				$icon->addClass('icon-info', true);
				break;
			case Icon::MAIL:
				$icon->addClass('icon-envelope', true);
				break;
			case Icon::PLUS:
				$icon->addClass('icon-plus', true);
				break;
			case Icon::LOCATION:
				$icon->addClass('icon-location', true);
				break;
			case Icon::LOCK:
				$icon->addClass('icon-lock', true);
				break;
			case Icon::OK:
				$icon->addClass('icon-ok', true);
				break;
			case Icon::PRINTING:
				$icon->addClass('icon-print', true);
				break;
			case Icon::SEARCH:
				$icon->addClass('icon-search', true);
				break;
			case Icon::SIGNUP:
				$icon->addClass('icon-signup', true);
				break;
			case Icon::UP:
				$icon->addClass('icon-arrow-up', true);
				break;
			case Icon::USERS:
				$icon->addClass('icon-users', true);
				break;
		}
	}
}
