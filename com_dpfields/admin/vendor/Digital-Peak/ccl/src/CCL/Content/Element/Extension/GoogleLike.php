<?php

namespace CCL\Content\Element\Extension;

use CCL\Content\Element\Basic\Element;

class GoogleLike extends Element
{

	public static $LANGUAGES = array(
		'ar',
		'bg',
		'ca',
		'zh-CN',
		'zh-TW',
		'hr',
		'cs',
		'da',
		'nl',
		'en-GB',
		'en-US',
		'et',
		'fil',
		'fi',
		'fr',
		'de',
		'el',
		'iw',
		'hi',
		'hu',
		'id',
		'it',
		'ja',
		'ko',
		'lv',
		'lt',
		'ms',
		'no',
		'fa',
		'pl',
		'pt-BR',
		'pt-PT',
		'ro',
		'ru',
		'sr',
		'sk',
		'sl',
		'es',
		'es-419',
		'sv',
		'th',
		'tr',
		'uk',
		'vi'
	);

	public function __construct($id, $url, array $classes = [], array $attributes = [])
	{
		$attributes['data-href'] = $url;

		$classes[] = 'g-plusone';
		$this->setProtectedClass('g-plusone');

		parent::__construct($id, $classes, $attributes);

		$language = '';
		if (key_exists('language', $attributes)) {
			$language = '//<![CDATA[{lang:"' . $attributes['language'] . '"}//]]>';
		}

		$this->setContent('<script type="text/javascript" src="//apis.google.com/js/plusone.js">' . $language . '</script>');
	}

	public static function getCorrectLanguage($language)
	{
		$tmpLanguage = $language;
		if (! in_array($tmpLanguage, self::$LANGUAGES)) {
			$tmpLanguage = substr($language, 0, strpos($language, '-'));
		}
		if (! in_array($tmpLanguage, self::$LANGUAGES)) {
			$tmpLanguage = 'en';
		}

		return $tmpLanguage;
	}
}
