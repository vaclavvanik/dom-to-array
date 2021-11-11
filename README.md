# DomToArray

This package provides an easy way to convert [DOMDocument](https://www.php.net/manual/en/class.domdocument.php) into array.

`DomToArray` supports attributes, cdata and array like elements.

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
use VaclavVanik\DomToArray;

$doc = new DOMDocument();
$doc->loadXML('<root/>');

$result = DomToArray::toArray($doc);
// $result = ['root' => ''];
```

### Array elements

Multiple elements with same name will create multi-dimensional array.

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
    <collection type="any"/>
    <author lang="English">Tolkien</author>
    <guy lang="Black Speech">
        <name>Sauron</name>
        <weapon>Evil Eye</weapon>
    </guy>
    <guy lang="Elvish">
        <name>Gandalf</name>
        <weapon>Staff</weapon>
    </guy>
</root>
```

This will result in:

```php
$result = [
    'root' => [
        'collection' => '',
        'collection@type' => 'any',
        'author' => 'Tolkien',
        'author@lang' => 'English',
        'guy' => [
            [
                'name' => 'Sauron',
                'weapon' => 'Evil Eye',
                'guy@lang' => 'Black Speech',
            ],
            [
                'name' => 'Gandalf',
                'weapon' => 'Staff',
                'guy@lang' => 'Elvish',
            ],
        ],
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
