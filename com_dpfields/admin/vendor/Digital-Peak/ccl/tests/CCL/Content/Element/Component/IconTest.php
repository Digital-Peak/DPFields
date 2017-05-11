<?php

namespace CCL\Tests\Content\Element\Component;

use CCL\Content\Element\Component\Icon;
use PHPUnit\Framework\TestCase;
use CCL\Content\Visitor\ElementVisitorInterface;

class IconTest extends TestCase
{

	public function testType()
	{
		$e = new Icon('test', Icon::CALENDAR);

		$this->assertEquals(Icon::CALENDAR, $e->getType());
	}

	public function testInvalidType()
	{
		$e = new Icon('test', 'invalid');

		$this->assertEquals(Icon::PLUS, $e->getType());
	}

	public function testAccept()
	{
		$visitor = $this->getMockBuilder(ElementVisitorInterface::class)->getMock();
		$visitor->expects($this->once())->method('visitIcon');

		$e = new Icon('test', Icon::CALENDAR);
		$e->accept($visitor);
	}
}
