<?php

namespace CCL\Tests\Content\Element\Basic;

use PHPUnit\Framework\TestCase;
use CCL\Content\Element\Extension\FacebookComment;
use CCL\Content\Visitor\ElementVisitorInterface;

class FacebookCommentTest extends TestCase
{

	public function testAttributes()
	{
		$e = new FacebookComment('test', 'https://digital-peak.com');

		$this->assertContains('https://digital-peak.com', $e->getAttributes());
	}

	public function testAccept()
	{
		$visitor = $this->getMockBuilder(ElementVisitorInterface::class)->getMock();
		$visitor->expects($this->once())->method('visitFacebookComment');

		$e = new FacebookComment('test', 'https://digital-peak.com');
		$e->accept($visitor);
	}
}
