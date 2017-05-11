# CCL a simple library for CMS extensions

[![Build Status](https://travis-ci.org/Digital-Peak/ccl.svg?branch=unstable)](https://travis-ci.org/Digital-Peak/ccl)
[![Coverage Status](https://coveralls.io/repos/github/Digital-Peak/ccl/badge.svg?branch=unstable)](https://coveralls.io/github/Digital-Peak/ccl?branch=unstable)

## Status
CCL means **C**ross **C**MS **L**ibrary. This is a fresh project under heavy development. The target is to provide a library which allows to use one code base for an extension on different CMS's like Joomla or Wordpress.

## Installation via Composer
Add `"Digital-Peak/ccl": "dev-unstable"` to the require block in your composer.json and then run `composer install`.

```json
{
	"require": {
            "Digital-Peak/ccl": "dev-unstable"
        },
        "repositories": [
            {
                "url": "https://github.com/Digital-Peak/ccl.git",
                "type": "git"
            }
        ]
     }
}
```

Alternatively, you can simply run the following from the command line:

```sh
composer require Digital-Peak/ccl "dev-unstable"
```

## Usage
Check out the examples in the examples folder or read the [documentation](docs). Current main functionality is to build content trees like:

```php
$container = new \CCL\Content\Element\Basic\Container('demo');

// Build the tree
$container->addChild(new \CCL\Content\Element\Basic\Paragraph('child'))->setContent('Paragraph');
$container->addChild(new \CCL\Content\Element\Component\Alert('alert'))->setContent('I am an alert box!');

$container->accept(new \CCL\Content\Visitor\Html\Framework\BS4());

// Traverse the tree
$domBuilder = new \CCL\Content\Visitor\Html\DomBuilder();
$container->accept($domBuilder);

echo $domBuilder->render();
```

Which produces: 
```html
<div id="demo">
<p id="demo-child">Paragraph</p>
<div class="alert alert-info" id="demo-alert">I am an alert box!</div>
</div>
```

## About

### Requirements
CCL works with PHP 5.6 or above.

### Submitting bugs and feature requests
Bugs and feature request are tracked on [GitHub](https://github.com/Digital-Peak/ccl/issues)

### Author
Allon Moritz for Digital Peak - <http://twitter.com/digitpeak><br />
See also the list of [contributors](https://github.com/Digital-Peak/ccl/contributors) which participated in this project.

### License
CCL is licensed under the MIT License - see the `LICENSE` file for details.