<?php

namespace CCL\Content\Element\Extension;

use CCL\Content\Element\Basic\Element;

class FacebookComment extends Element
{

	public static $LANGUAGES = array(
		'ca_ES',
		'cs_CZ',
		'cy_GB',
		'da_DK',
		'de_DE',
		'eu_ES',
		'en_PI',
		'en_UD',
		'ck_US',
		'en_US',
		'es_LA',
		'es_CL',
		'es_CO',
		'es_ES',
		'es_MX',
		'es_VE',
		'fb_FI',
		'fi_FI',
		'fr_FR',
		'gl_ES',
		'hu_HU',
		'it_IT',
		'ja_JP',
		'ko_KR',
		'nb_NO',
		'nn_NO',
		'nl_NL',
		'pl_PL',
		'pt_BR',
		'pt_PT',
		'ro_RO',
		'ru_RU',
		'sk_SK',
		'sl_SI',
		'sv_SE',
		'th_TH',
		'tr_TR',
		'ku_TR',
		'zh_CN',
		'zh_HK',
		'zh_TW',
		'fb_LT',
		'af_ZA',
		'sq_AL',
		'hy_AM',
		'az_AZ',
		'be_BY',
		'bn_IN',
		'bs_BA',
		'bg_BG',
		'hr_HR',
		'nl_BE',
		'en_GB',
		'eo_EO',
		'et_EE',
		'fo_FO',
		'fr_CA',
		'ka_GE',
		'el_GR',
		'gu_IN',
		'hi_IN',
		'is_IS',
		'id_ID',
		'ga_IE',
		'jv_ID',
		'kn_IN',
		'kk_KZ',
		'la_VA',
		'lv_LV',
		'li_NL',
		'lt_LT',
		'mk_MK',
		'mg_MG',
		'ms_MY',
		'mt_MT',
		'mr_IN',
		'mn_MN',
		'ne_NP',
		'pa_IN',
		'rm_CH',
		'sa_IN',
		'sr_RS',
		'so_SO"',
		'sw_KE',
		'tl_PH',
		'ta_IN',
		'tt_RU',
		'te_IN',
		'ml_IN',
		'uk_UA',
		'uz_UZ',
		'vi_VN',
		'xh_ZA',
		'zu_ZA',
		'km_KH',
		'tg_TJ',
		'ar_AR',
		'he_IL',
		'ur_PK',
		'fa_IR',
		'sy_SY',
		'yi_DE',
		'gn_PY',
		'qu_PE',
		'ay_BO',
		'se_NO',
		'ps_AF',
		'tl_ST'
	);

	public function __construct($id, $url, array $classes = [], array $attributes = [])
	{
		$classes[] = 'fb-comments';
		$this->setProtectedClass('fb-comments');

		$attributes['data-href'] = $url;

		$this->setNumberOfPostsLimit(5);
		$this->setColorScheme('light');

		parent::__construct($id, $classes, $attributes);
	}

	public static function getCorrectLanguage($language)
	{
		$tmpLanguage = $language;
		$tmpLanguage = str_replace('-', '_', $tmpLanguage);
		if (! in_array($tmpLanguage, self::$LANGUAGES)) {
			$tmpLanguage = 'en_US';
		}
		return $tmpLanguage;
	}

	public function setNumberOfPostsLimit($max)
	{
		$this->addAttribute('data-numposts', (int) $max);
	}

	public function setColorScheme($scheme)
	{
		$this->addAttribute('data-colorscheme', $scheme);
	}
}
