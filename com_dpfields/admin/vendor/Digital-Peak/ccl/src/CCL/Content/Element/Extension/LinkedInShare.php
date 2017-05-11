<?php

namespace CCL\Content\Element\Extension;

use CCL\Content\Element\Basic\Element;

class LinkedInShare extends Element
{

	public static $LANGUAGES = array(
		'en' => 'en_US',
		'fr' => 'fr_FR',
		'es' => 'es_ES',
		'ru' => 'ru_RU',
		'de' => 'de_DE',
		'it' => 'it_IT',
		'pt' => 'pt_BR',
		'ro' => 'ro_RO',
		'tr' => 'tr_TR',
		'ja' => 'ja_JP',
		'in' => 'in_ID',
		'ms' => 'ms_MY',
		'ko' => 'ko_KR',
		'sv' => 'sv_SE',
		'cs' => 'cs_CZ',
		'nl' => 'nl_NL',
		'pl' => 'pl_PL',
		'no' => 'no_NO',
		'da' => 'da_DK'
	);

	public function __construct($id, $url, array $classes = [], array $attributes = [])
	{
		parent::__construct($id, $classes, $attributes);

		$language = '';
		if (key_exists('language', $attributes)) {
			$language = '<![CDATA[{lang:"' . $attributes['language'] . '"}]]>';
		}

		$counter = 'data-counter="right"';
		if ($attributes['counter'] == 'vertical') {
			$counter = 'data-counter="top"';
		}
		if ($attributes['counter'] == 'none') {
			$counter = '';
		}

		$this->setContent('<script type="text/javascript" src="//platform.linkedin.com/in.js">' . $language . '</script>
			<script type="IN/Share" data-url="' . $url . '" ' . $counter . ' data-showzero="true"></script>');
	}

	public static function getCorrectLanguage($language)
	{
		$tmpLanguage = substr($language, 0, strpos($language, '-'));
		if (! array_key_exists($tmpLanguage, self::$LANGUAGES)) {
			$tmpLanguage = 'en_US';
		} else {
			$tmpLanguage = self::$LANGUAGES[$tmpLanguage];
		}

		return $tmpLanguage;
	}
}
