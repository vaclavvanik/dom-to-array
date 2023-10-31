<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomToArray;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VaclavVanik\DomToArray\DomOptions;
use VaclavVanik\DomToArray\DomToArray;

final class DomToArrayTest extends TestCase
{
    public function testConvert(): void
    {
        $result = [
            'root' => '',
            'root@attr' => 'val',
        ];

        $doc = new DOMDocument();
        $doc->loadXML('<root attr="val"/>');

        $this->assertSame($result, DomToArray::toArray($doc));
    }

    public function testConvertSkipAttributes(): void
    {
        $result = ['root' => ''];

        $doc = new DOMDocument();
        $doc->loadXML('<root attr="val"/>');

        $this->assertSame($result, DomToArray::toArrayWithOptions($doc, self::domOptionSkipAttributes()));
    }

    public function testConvertEmptyDomDocument(): void
    {
        $doc = new DOMDocument();

        $this->assertSame([], DomToArray::toArray($doc));
    }

    public function testConvertArray(): void
    {
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
                'unknown' => [
                    'name' => [
                        'King of the Dead',
                        'Ghân-buri-Ghân',
                    ],
                ],
            ],
        ];

        $doc = self::domFromFile(__DIR__ . '/_files/array.xml');

        $this->assertSame($result, DomToArray::toArray($doc));
    }

    public function testConvertAttributes(): void
    {
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

        $doc = self::domFromFile(__DIR__ . '/_files/attributes.xml');

        $this->assertSame($result, DomToArray::toArray($doc));
    }

    public function testConvertAttributesSkipAttributes(): void
    {
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

        $doc = self::domFromFile(__DIR__ . '/_files/attributes.xml');

        $this->assertSame($result, DomToArray::toArrayWithOptions($doc, self::domOptionSkipAttributes()));
    }

    public function testConvertCdata(): void
    {
        $result = [
            'root' => [
                'good_guy' => [
                    'name' => '<h1>Gandalf</h1>',
                    'weapon' => 'Staff',
                ],
            ],
        ];

        $doc = self::domFromFile(__DIR__ . '/_files/cdata.xml');

        $this->assertSame($result, DomToArray::toArray($doc));
    }

    private static function domFromFile(string $file): DOMDocument
    {
        $doc = new DOMDocument();
        $doc->load($file);

        return $doc;
    }

    private static function domOptionSkipAttributes(): DomOptions
    {
        return DomOptions::fromArray([DomOptions::SKIP_ATTRIBUTES => true]);
    }
}
