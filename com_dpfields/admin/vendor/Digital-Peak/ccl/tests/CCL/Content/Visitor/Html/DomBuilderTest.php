<?php

namespace CCL\Tests\Content\Visitor\Framework;

use CCL\Content\Framework;
use PHPUnit\Framework\TestCase;
use CCL\Content\Element\Basic\Element;
use CCL\Content\Element\Basic\Container;
use CCL\Content\Visitor\Html\DomBuilder;
use CCL\Content\Element\Extension\FacebookComment;

class DomBuilderTest extends TestCase
{

	public function testRender()
	{
		$builder = new DomBuilder();

		$e = new Element('test');
		$e->accept($builder);

		$this->assertXmlStringEqualsXmlString('<div id="test"></div>', $builder->render());
	}

	public function testRenderWithContent()
	{
		$builder = new DomBuilder();

		$e = new Element('test');
		$e->setContent('unit');
		$e->accept($builder);

		$this->assertXmlStringEqualsXmlString('<div id="test">unit</div>', $builder->render($e));
	}

	public function testRenderWithHTMLContent()
	{
		$builder = new DomBuilder();

		$e = new Element('test');
		$e->setContent('<p>unit</p>');
		$e->accept($builder);

		$this->assertXmlStringEqualsXmlString('<div id="test"><p>unit</p></div>', $builder->render($e));
	}

	public function testRenderWithInvalidHTMLContent()
	{
		$builder = new DomBuilder();

		$e = new Element('test');
		$e->setContent('<p>unit');
		$e->accept($builder);

		$this->assertEquals('<div id="test"><p>unit</div>', $builder->render($e));
	}

	public function testRenderContainer()
	{
		$builder = new DomBuilder();

		$e = new Container('test');
		$el = $e->addChild(new Container('unit'));
		$el->setContent('unit test');
		$e->accept($builder);

		$this->assertXmlStringEqualsXmlString('<div id="test"><div id="test-unit">unit test</div></div>', $builder->render($e));
	}

	public function testRenderContainerMultipleChildren()
	{
		$builder = new DomBuilder();

		$e = new Container('test');
		$e->addChild(new Container('unit1'))->addChild(new Container('unit1.1'));

		$c = $e->addChild(new Container('unit2'));
		$c->addChild(new Container('unit2.1'));
		$c->addChild(new Container('unit2.2'));

		$e->accept($builder);

		$string  = '<div id="test">';
		$string .= '<div id="test-unit1"><div id="test-unit1-unit1.1"></div></div>';
		$string .= '<div id="test-unit2"><div id="test-unit2-unit2.1"></div><div id="test-unit2-unit2.2"></div></div>';
		$string .= '</div>';

		$this->assertXmlStringEqualsXmlString($string, $builder->render($e));
	}

	public function testRenderContainerMultipleChildrenWithPrefix()
	{
		$builder = new DomBuilder();

		$e = new Container('test', array(), array('ccl-prefix' => 'foo-'));
		$e->addChild(new Container('unit'))
		->addChild(new Container('bar', array('doo')))
		->addChild(new Container('john'));

		$e->accept($builder);

		$string  = '<div id="test">';
		$string .= '<div id="test-unit">';
		$string .= '<div id="test-unit-bar" class="foo-doo">';
		$string .= '<div id="test-unit-bar-john"></div>';
		$string .= '</div>';
		$string .= '</div>';
		$string .= '</div>';

		$this->assertXmlStringEqualsXmlString($string, $builder->render($e));
	}

	public function testRenderExtension()
	{
		$builder = new DomBuilder();

		$e = new FacebookComment('test', 'https://digital-peak.com');
		$e->accept($builder);

		$this->assertXmlStringEqualsXmlString('<div class="fb-comments" data-href="https://digital-peak.com" id="test"></div>', $builder->render());
	}
}
