<?php

declare(strict_types=1);

namespace VaclavVanikTest\DomToArray\Exception;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use VaclavVanik\DomToArray\Exception\DomEmpty;

final class DomEmptyTest extends TestCase
{
    public function testFromThrowable(): void
    {
        $doc = new DOMDocument();

        $domEmpty = DomEmpty::fromDom($doc);

        $this->assertSame($doc, $domEmpty->getDoc());
        $this->assertSame('Cannot convert empty ' . DOMDocument::class, $domEmpty->getMessage());
    }
}
