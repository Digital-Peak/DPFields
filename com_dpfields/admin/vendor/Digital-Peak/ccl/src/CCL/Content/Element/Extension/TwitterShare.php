<?php

namespace CCL\Content\Element\Extension;

use CCL\Content\Element\Basic\Link;

class TwitterShare extends Link
{

	public static $LANGUAGES = array(
		'ko',
		'fr',
		'ja',
		'it',
		'id',
		'en',
		'nl',
		'pt',
		'ru',
		'es',
		'de',
		'tr'
	);

	public function __construct($id, $url, array $classes = [], array $attributes = [])
	{
		$attributes['data-href'] = $url;

		$classes[] = 'twitter-share-button';
		$this->setProtectedClass('twitter-share-button');

		parent::__construct($id, '//twitter.com/share', '', $classes, $attributes);
	}

	public static function getCorrectLanguage($language)
	{
		$tmpLanguage = $language;
		$tmpLanguage = substr($language, 0, strpos($language, '-'));
		if (! in_array($tmpLanguage, self::$LANGUAGES)) {
			$tmpLanguage = 'en';
		}
		return $tmpLanguage;
	}
}
