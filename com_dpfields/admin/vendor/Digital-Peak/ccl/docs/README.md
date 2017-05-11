# Documentation

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

## Package description
CCL comes with the following packages:

## [Content](Content)
 Package to build content trees. HTML strings can be generated out of such a tree decorated with different frontend frameworks classes like Bootstrap or Uikit and icon frameworks like Fontawesome.