# DomToArray

[![CI](https://github.com/vaclavvanik/dom-to-array/actions/workflows/ci.yml/badge.svg)](https://github.com/vaclavvanik/dom-to-array/actions/workflows/ci.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/vaclavvanik/dom-to-array)](https://packagist.org/packages/vaclavvanik/dom-to-array)
[![Total Downloads](https://img.shields.io/packagist/dt/vaclavvanik/dom-to-array)](https://packagist.org/packages/vaclavvanik/dom-to-array)
[![License](https://img.shields.io/packagist/l/vaclavvanik/dom-to-array)](LICENSE.md)

This package provides an easy way to convert [DOMDocument](https://www.php.net/manual/en/class.domdocument.php) to PHP array.

`DomToArray` supports attributes, cdata and array like elements.

Main usage is to convert any XML API response to array. DomToArray consumes good old PHP DOMDocument object.
XML API responses are strings which could be flawlessly loaded to DOMDocument with
[vaclavvanik/dom-loader](https://github.com/vaclavvanik/dom-loader).

## Install

You can install this package via composer.

``` bash
composer require vaclavvanik/dom-to-array
```

## Usage

Simply pass DOMDocument

```php
<?php

declare(strict_types=1);

use DOMDocument;
use VaclavVanik\DomToArray\DomToArray;

$doc = new DOMDocument();
$doc->loadXML('<root/>');

$result = DomToArray::toArray($doc);
// $result = ['root' => ''];
```

### Array elements

Multiple elements with same name will create multidimensional array.

```xml
<root>
    <name>guy collection</name>
    <good_guy>
        <name>Luke Skywalker</name>
        <weapon>Lightsaber</weapon>
    </good_guy>
    <good_guy>
        <name>Gandalf</name>
        <weapon>Staff</weapon>
    </good_guy>
    <bad_guy>
        <name>Saruman</name>
        <weapon>Staff</weapon>
    </bad_guy>
    <bad_guy>
        <name>Sauron</name>
        <weapon>Ring</weapon>
    </bad_guy>
</root>
```

This will result in:

```php
$result = [
    'root' => [
        'name' => 'guy collection',
        'good_guy' => [
            [
                'name' => 'Luke Skywalker',
                'weapon' => 'Lightsaber',
            ],
            [
                'name' => 'Gandalf',
                'weapon' => 'Staff',
            ],
        ],
        'bad_guy' => [
            [
                'name' => 'Saruman',
                'weapon' => 'Staff',
            ],
            [
                'name' => 'Sauron',
                'weapon' => 'Ring',
            ],
        ],
    ],
];
```

### Attributes

Element attributes create key => value like `element_name@attribute_name` => `attribute_value`

```xml
<root attr="val">
    <single type="any"/>
    <collection type="any1"/>
    <collection type="any2"/>
    <author lang="English">Tolkien</author>
    <guy lang="Black Speech">
        <name weapon="Ring">Sauron</name>
        <weapon>Ring</weapon>
    </guy>
    <guy lang="Elvish">
        <name weapon="Staff">Gandalf</name>
        <weapon>Staff</weapon>
    </guy>
    <bad_guy lang="Unknown">
        <name weapon="Staff">Saruman</name>
        <name weapon="Ring">Sauron</name>
    </bad_guy>
</root>
```

This will result in:

```php
$result = [
    'root' => [
        'single' => '',
        'single@type' => 'any',
        'collection' => [
            ['collection@type' => 'any1'],
            ['collection@type' => 'any2'],
        ],
        'author' => 'Tolkien',
        'author@lang' => 'English',
        'guy' => [
            [
                'name' => 'Sauron',
                'name@weapon' => 'Ring',
                'weapon' => 'Ring',
                'guy@lang' => 'Black Speech',
            ],
            [
                'name' => 'Gandalf',
                'name@weapon' => 'Staff',
                'weapon' => 'Staff',
                'guy@lang' => 'Elvish',
            ],
        ],
        'bad_guy' => [
            [
                'name' => 'Saruman',
                'name@weapon' => 'Staff',
            ],
            [
                'name' => 'Sauron',
                'name@weapon' => 'Ring',
            ],
        ],
        'bad_guy@lang' => 'Unknown',
    ],
    'root@attr' => 'val',
];
```

### Cdata

Cdata are convert same as element text content.

```xml
<root>
    <good_guy>
        <name><![CDATA[<h1>Gandalf</h1>]]></name>
        <weapon>Staff</weapon>
    </good_guy>
</root>
```

This will result in:

```php
$result = [
    'root' => [
        'good_guy' => [
            'name' => '<h1>Gandalf</h1>',
            'weapon' => 'Staff',
        ],
    ],
];
```

### Comments and processing instructions

Comments and processing instructions are skipped. Text they split is joined.

```xml
<root>Gandalf <!-- comment --><?target data?>the Grey</root>
```

```php
$result = [
    'root' => 'Gandalf the Grey',
];
```

## DomOptions

Options are passed to `DomToArray::toArrayWithOptions()`.

```php
<?php

declare(strict_types=1);

use DOMDocument;
use VaclavVanik\DomToArray\DomOptions;
use VaclavVanik\DomToArray\DomToArray;

$domOptions = DomOptions::fromArray([DomOptions::SKIP_ATTRIBUTES => true]);
$result = DomToArray::toArrayWithOptions($doc, $domOptions);
```

### DomOptions::SKIP_ATTRIBUTES

Sometimes it is useful to work only with elements (attributes are not needed).

```xml
<root attr="val">
    <single type="any"/>
    <collection type="any1"/>
    <collection type="any2"/>
    <author lang="English">Tolkien</author>
    <guy lang="Black Speech">
        <name weapon="Ring">Sauron</name>
        <weapon>Ring</weapon>
    </guy>
    <guy lang="Elvish">
        <name weapon="Staff">Gandalf</name>
        <weapon>Staff</weapon>
    </guy>
    <bad_guy lang="Unknown">
        <name weapon="Staff">Saruman</name>
        <name weapon="Ring">Sauron</name>
    </bad_guy>
</root>
```
This will result in:

```php
$result = [
    'root' => [
        'single' => '',
        'collection' => ['', ''],
        'author' => 'Tolkien',
        'guy' => [
            [
                'name' => 'Sauron',
                'weapon' => 'Ring',
            ],
            [
                'name' => 'Gandalf',
                'weapon' => 'Staff',
            ],
        ],
        'bad_guy' => [
            'name' => ['Saruman', 'Sauron'],
        ],
    ],
];
```

### DomOptions::KEEP_MIXED_CONTENT

By default an element that holds both text and child elements is converted to its
text only and the child elements are dropped.

```xml
<root>Gandalf the <colour>Grey</colour></root>
```

```php
$result = [
    'root' => 'Gandalf the ',
];
```

With `DomOptions::KEEP_MIXED_CONTENT` the text is kept under the `@value` key next
to the child elements.

```php
$domOptions = DomOptions::fromArray([DomOptions::KEEP_MIXED_CONTENT => true]);
$result = DomToArray::toArrayWithOptions($doc, $domOptions);
```

```php
$result = [
    'root' => [
        '@value' => 'Gandalf the ',
        'colour' => 'Grey',
    ],
];
```

Text is concatenated as-is (whitespace included) and its position relative to the
child elements is not preserved.

### DomOptions::OMIT_ROOT_ELEMENT

By default the result is wrapped in the name of the root element.

```xml
<root lang="Elvish">
    <name>Gandalf</name>
    <weapon>Staff</weapon>
</root>
```

```php
$result = [
    'root' => [
        'name' => 'Gandalf',
        'weapon' => 'Staff',
    ],
    'root@lang' => 'Elvish',
];
```

With `DomOptions::OMIT_ROOT_ELEMENT` the wrapper is removed and the children are
returned directly.

```php
$domOptions = DomOptions::fromArray([DomOptions::OMIT_ROOT_ELEMENT => true]);
$result = DomToArray::toArrayWithOptions($doc, $domOptions);
```

```php
$result = [
    'name' => 'Gandalf',
    'weapon' => 'Staff',
    'root@lang' => 'Elvish',
];
```

Root element attributes keep their `root@attribute` keys (and are still removed by
`DomOptions::SKIP_ATTRIBUTES`). A root element with only text content is returned
as `['@value' => '...']`, an empty root element as `[]`.

### DomOptions::USE_ATTRIBUTE_NODE_NAME

By default attributes are keyed by their local name, so two attributes that only
differ by namespace prefix collide.

```xml
<root xmlns:x="urn:x" x:type="qualified" type="plain"/>
```

```php
$result = [
    'root' => '',
    'root@type' => 'plain',
];
```

With `DomOptions::USE_ATTRIBUTE_NODE_NAME` the full attribute node name (including
the namespace prefix) is used.

```php
$domOptions = DomOptions::fromArray([DomOptions::USE_ATTRIBUTE_NODE_NAME => true]);
$result = DomToArray::toArrayWithOptions($doc, $domOptions);
```

```php
$result = [
    'root' => '',
    'root@x:type' => 'qualified',
    'root@type' => 'plain',
];
```

## Run check - coding standards and php-unit

Install dependencies:

```bash
make install
```

Run check:

```bash
make check
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
