<?php

namespace CCL\Tests\Content\Element\Basic;

use CCL\Content\Element\Basic\Link;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{

	public function testRender()
	{
		$e = new Link('test', 'https://digital-peak.com');

		$this->assertContains('https://digital-peak.com', $e->getAttributes());
	}
}
