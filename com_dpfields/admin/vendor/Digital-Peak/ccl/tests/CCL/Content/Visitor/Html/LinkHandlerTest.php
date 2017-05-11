<?php

namespace CCL\Tests\Content\Visitor\Framework;

use CCL\Content\Element\Basic\Link;
use CCL\Content\Visitor\Html\LinkHandler;
use PHPUnit\Framework\TestCase;

class LinkHandlerTest extends TestCase
{

	public function testRel()
	{
		$handler = new LinkHandler('http://example.com');

		$e = new Link('test', 'http://unit.com');
		$e->accept($handler);

		$this->assertArrayHasKey('rel', $e->getAttributes());
	}

	public function testNoRel()
	{
		$handler = new LinkHandler('http://example.com');

		$e = new Link('test', 'http://example.com/test');
		$e->accept($handler);

		$this->assertArrayNotHasKey('rel', $e->getAttributes());
	}
}
