<?php

namespace CCL\Content\Element\Component;

use CCL\Content\Element\Basic\Element;

/**
 * An icon representation.
 */
class Icon extends Element
{
	/**
	 * The calendar icon.
	 *
	 * @var string
	 */
	const CALENDAR = 'calendar';

	/**
	 * The cancel icon.
	 *
	 * @var string
	 */
	const CANCEL = 'cancel';

	/**
	 * The delete icon.
	 *
	 * @var string
	 */
	const DELETE = 'delete';

	/**
	 * The down icon.
	 *
	 * @var string
	 */
	const DOWN = 'down';

	/**
	 * The download icon.
	 *
	 * @var string
	 */
	const DOWNLOAD = 'download';

	/**
	 * The edit icon.
	 *
	 * @var string
	 */
	const EDIT = 'edit';

	/**
	 * The file icon.
	 *
	 * @var string
	 */
	const FILE = 'file';

	/**
	 * The info icon.
	 *
	 * @var string
	 */
	const INFO = 'info';

	/**
	 * The location icon.
	 *
	 * @var string
	 */
	const LOCATION = 'location';

	/**
	 * The lock icon.
	 *
	 * @var string
	 */
	const LOCK = 'lock';

	/**
	 * The mail icon.
	 *
	 * @var string
	 */
	const MAIL = 'mail';

	/**
	 * The ok icon.
	 *
	 * @var string
	 */
	const OK = 'ok';

	/**
	 * The plus icon.
	 *
	 * @var string
	 */
	const PLUS = 'plus';

	/**
	 * The edit icon.
	 *
	 * @var string
	 */
	const PRINTING = 'print';

	/**
	 * The search icon.
	 *
	 * @var string
	 */
	const SEARCH = 'search';

	/**
	 * The signup icon.
	 *
	 * @var string
	 */
	const SIGNUP = 'signup';

	/**
	 * The up icon.
	 *
	 * @var string
	 */
	const UP = 'up';

	/**
	 * The users icon.
	 *
	 * @var string
	 */
	const USERS = 'users';

	/**
	 * Array which holds all available icons.
	 *
	 * @var array
	 */
	private static $ALL_ICONS = [
		self::CALENDAR,
		self::CANCEL,
		self::DELETE,
		self::DOWN,
		self::DOWNLOAD,
		self::EDIT,
		self::FILE,
		self::INFO,
		self::MAIL,
		self::PLUS,
		self::LOCATION,
		self::LOCK,
		self::OK,
		self::PRINTING,
		self::SEARCH,
		self::SIGNUP,
		self::UP,
		self::USERS
	];

	/**
	 * The type of the icon.
	 *
	 * @var string
	 */
	private $type = self::PLUS;

	/**
	 * Prepares the icon with the given strategy if available.
	 *
	 * @param string $id
	 * @param string $type
	 * @param array $classes
	 * @param array $attributes
	 */
	public function __construct($id, $type, array $classes = [], array $attributes = [])
	{
		parent::__construct($id, $classes, $attributes);

		if (! in_array($type, self::$ALL_ICONS)) {
			$type = self::PLUS;
		}

		$this->type = $type;
	}

	/**
	 * Returns the type of the icon.
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}
}
