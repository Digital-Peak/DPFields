<?php

namespace CCL\Tests\Content\Element\Basic;

use PHPUnit\Framework\TestCase;
use CCL\Content\Element\Basic\Container;
use CCL\Content\Element\Basic\TextBlock;
use CCL\Content\Visitor\ElementVisitorInterface;

class ContainerTest extends TestCase
{

	public function testAddChild()
	{
		$e = new Container('test');
		$el = $e->addChild(new Container('unit'));

		$this->assertInstanceOf(Container::class, $el);

		$this->assertEquals($el, $e->getChildren()[0]);
	}

	public function testAccept()
	{
		$visitor = $this->getMockBuilder(ElementVisitorInterface::class)->getMock();
		$visitor->expects($this->once())->method('visitContainer');
		$visitor->expects($this->once())->method('visitTextBlock');

		$e = new Container('test');
		$e->addChild(new TextBlock('unit'));
		$e->accept($visitor);
	}
}
