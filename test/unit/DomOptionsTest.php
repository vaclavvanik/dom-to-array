<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomToArray;

use PHPUnit\Framework\TestCase;
use VaclavVanik\DomToArray\DomOptions;

final class DomOptionsTest extends TestCase
{
    public function testFromArray(): void
    {
        $array = [DomOptions::SKIP_ATTRIBUTES => true];

        $options = DomOptions::fromArray($array);

        $this->assertSame($array[DomOptions::SKIP_ATTRIBUTES], $options->getSkipAttributes());
    }

    public function testFromArrayDefaultsToFalse(): void
    {
        $options = DomOptions::fromArray([]);

        $this->assertFalse($options->getSkipAttributes());
    }

    public function testFromArrayCastsToBool(): void
    {
        $options = DomOptions::fromArray([DomOptions::SKIP_ATTRIBUTES => 1]);

        $this->assertTrue($options->getSkipAttributes());
    }

    public function testKeepMixedContentDefaultsToFalse(): void
    {
        $options = DomOptions::fromArray([]);

        $this->assertFalse($options->getKeepMixedContent());
    }

    public function testFromArrayKeepMixedContent(): void
    {
        $options = DomOptions::fromArray([DomOptions::KEEP_MIXED_CONTENT => true]);

        $this->assertTrue($options->getKeepMixedContent());
    }
}
