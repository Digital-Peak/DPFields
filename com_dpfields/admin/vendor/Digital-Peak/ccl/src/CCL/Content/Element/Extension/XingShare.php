<?php

namespace CCL\Content\Element\Extension;

use CCL\Content\Element\Basic\Element;

class XingShare extends Element
{

	public function __construct($id, array $classes = [], array $attributes = [])
	{
		$attributes['data-type'] = 'xing/share';

		parent::__construct($id, $classes, $attributes);

		$this->setContent('<script>;(function (d, s) {
    var x = d.createElement(s),
      s = d.getElementsByTagName(s)[0];
      x.src = "https://www.xing-share.com/plugins/share.js";
      s.parentNode.insertBefore(x, s);
  })(document, "script");</script>');
	}
}
