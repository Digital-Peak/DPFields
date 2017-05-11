<?php

namespace CCL\Content\Element\Component;

use CCL\Content\Element\Basic\Container;

/**
 * An alert representation.
 */
class Alert extends Container
{

	/**
	 * The info alert.
	 *
	 * @var string
	 */
	const INFO = 'info';

	/**
	 * The success alert.
	 *
	 * @var string
	 */
	const SUCCESS = 'success';

	/**
	 * The warning alert.
	 *
	 * @var string
	 */
	const WARNING = 'warning';

	/**
	 * The danger alert.
	 *
	 * @var string
	 */
	const DANGER = 'danger';

	/**
	 * The type.
	 *
	 * @var unknown
	 */
	private $type = self::INFO;

	/**
	 * Initiates the alert of the given type.
	 *
	 * @param string $type
	 * @param array $classes
	 * @param array $attributes
	 */
	public function __construct($id, $type, array $classes = [], array $attributes = [])
	{
		parent::__construct($id, $classes, $attributes);

		if (! in_array($type, [self::INFO, self::SUCCESS, self::WARNING, self::DANGER])) {
			$type = self::INFO;
		}

		$this->type = $type;
	}

	/**
	 * Returns the type of alert.
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}
}
