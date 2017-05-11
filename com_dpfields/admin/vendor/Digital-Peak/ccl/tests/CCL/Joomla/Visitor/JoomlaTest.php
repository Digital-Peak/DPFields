<?php

namespace CCL\Tests\Joomla\Visitor;

use CCL\Content\Element\Basic\Table;
use CCL\Joomla\Visitor\Html\Joomla;
use PHPUnit\Framework\TestCase;

class JoomlaTest extends TestCase
{

	public function testTable()
	{
		$visitor = new Joomla();

		$e = new Table('test', []);
		$e->accept($visitor);

		$this->assertContains('table-bordered', $e->getClasses());
		$this->assertContains('table-hover', $e->getClasses());
	}
}
