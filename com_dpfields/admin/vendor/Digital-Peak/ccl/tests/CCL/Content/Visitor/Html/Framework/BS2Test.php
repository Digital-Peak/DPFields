<?php

namespace CCL\Tests\Content\Visitor\Html\Framework;

use CCL\Content\Framework;
use PHPUnit\Framework\TestCase;
use CCL\Content\Element\Component\Alert;
use CCL\Content\Visitor\Html\Framework\BS2;
use CCL\Content\Element\Component\Grid\Column;

class BS2Test extends TestCase
{

	public function testBS2Alert()
	{
		$a = new Alert('test', Alert::INFO);
		$f = new BS2();

		$f->visitAlert($a);
		$this->assertEquals('alert alert-info', $a->getAttributes()['class']);
	}

	public function testBS2GridColumnMin()
	{
		$c = new Column('test', 1);
		$f = new BS2();

		$f->visitGridColumn($c);
		$this->assertEquals('span1', $c->getAttributes()['class']);
	}

	public function testBS2GridColumnMiddle()
	{
		$c = new Column('test', 25);
		$f = new BS2();

		$f->visitGridColumn($c);
		$this->assertEquals('span3', $c->getAttributes()['class']);
	}

	public function testBS2GridColumnFull()
	{
		$c = new Column('test', 120);
		$f = new BS2();

		$f->visitGridColumn($c);
		$this->assertEquals('span12', $c->getAttributes()['class']);
	}
}
