# Content Package

The content package provides some element tree functionality. You can set up a tree of content elements and traverse it with visitors.


## Simple usage
If you add the foollowing code in your app:

```php
class ExampleVisitor extends \CCL\Content\Visitor\AbstractElementVisitor
{
	public function visitParagraph(\CCL\Content\Element\Basic\Paragraph $p)
	{
		$this->printElement($p);
	}

	public function visitTextBlock(\CCL\Content\Element\Basic\TextBlock $t)
	{
		$this->printElement($t);
	}

	private function printElement(\CCL\Content\Element\ElementInterface $element)
	{
		echo 'Found element: ' . $element . ' with content: ' . $element->getContent() . PHP_EOL;
	}
}

$container = new \CCL\Content\Element\Basic\Container('demo');

// Build the tree
$container->addChild(new \CCL\Content\Element\Basic\Paragraph('child1'))->setContent('Paragraph');
$container->addChild(new \CCL\Content\Element\Basic\TextBlock('child2'))->setContent('TextBlock');

// Traverse the tree
$container->accept(new ExampleVisitor());
```

It will produce the output:
```
Found element: demo-child1
 with content: Paragraph
Found element: demo-child2
 with content: TextBlock
```

## Build a HTML string
To build an HTML string out of a content tree, then use the following code:

```php
$container = new \CCL\Content\Element\Basic\Container('demo');

// Build the tree
$container->addChild(new \CCL\Content\Element\Basic\Paragraph('child1'))->setContent('Paragraph');
$container->addChild(new \CCL\Content\Element\Basic\TextBlock('child2'))->setContent('TextBlock');

// Traverse the tree
$domBuilder = new \CCL\Content\Visitor\Html\DomBuilder();
$container->accept($domBuilder);

echo $domBuilder->render();
```

It will echo:
```html
<div id="demo">
<p id="demo-child1">Paragraph</p>
<span id="demo-child2">TextBlock</span>
</div>
```

## Build a Bootstrap 4 alert box
To build an HTML string out of a content tree, then use the following code:

```php
$alert = new \CCL\Content\Element\Component\Alert('alert', \CCL\Content\Element\Component\Alert::DANGER);
$alert->setContent('I am an alert box!');

// Decorate with Bootstrap 4 classes
$alert->accept(new \CCL\Content\Visitor\Html\Framework\BS4());

// Traverse the tree
$domBuilder = new \CCL\Content\Visitor\Html\DomBuilder();
$alert->accept($domBuilder);

echo $domBuilder->render();
```

It will echo:
```html
<div class="alert alert-danger" id="alert">I am an alert box!</div>
```
More examples can be found in the examples directory. They are standalone scripts which can be executed without any additional dependencies. Don't forget to run `composer install` first in the root of the ccl package.