<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomToArray;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VaclavVanik\DomToArray\DomToArray;

final class DomToArrayTest extends TestCase
{
    public function testConstructor(): void
    {
        $doc = new DOMDocument();
        $doc->loadXML('<root/>');

        $this->assertSame(['root' => ''], DomToArray::toArray($doc));
    }

    public function testConvertArray(): void
    {
        $array = [
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

        $doc = $this->domFromFile(__DIR__ . '/_files/array.xml');

        $this->assertSame($array, DomToArray::toArray($doc));
    }

    public function testConvertAttributes(): void
    {
        $attributes = [
            'root' => [
                'bad_guy' => [
                    '@attributes' => ['lang' => 'Black Speech'],
                    'name' => 'Sauron',
                    'weapon' => 'Evil Eye',
                ],
            ],
        ];

        $doc = $this->domFromFile(__DIR__ . '/_files/attributes.xml');

        $this->assertSame($attributes, DomToArray::toArray($doc));
    }

    public function testConvertCdata(): void
    {
        $cdata = [
            'root' => [
                'good_guy' => [
                    'name' => '<h1>Gandalf</h1>',
                    'weapon' => 'Staff',
                ],
            ],
        ];

        $doc = $this->domFromFile(__DIR__ . '/_files/cdata.xml');

        $this->assertSame($cdata, DomToArray::toArray($doc));
    }

    public function testSimple(): void
    {
        $simple = [
            'root' => [
                'good_guy' => [
                    'name' => 'Luke Skywalker',
                    'weapon' => 'Lightsaber',
                ],
            ],
        ];

        $doc = $this->domFromFile(__DIR__ . '/_files/simple.xml');

        $this->assertSame($simple, DomToArray::toArray($doc));
    }

    private function domFromFile(string $file): DOMDocument
    {
        $doc = new DOMDocument();
        $doc->load($file);

        return $doc;
    }
}
