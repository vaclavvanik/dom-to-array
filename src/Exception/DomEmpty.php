<?php

declare(strict_types=1);

namespace VaclavVanik\DomToArray\Exception;

use DOMDocument;
use RuntimeException;

final class DomEmpty extends RuntimeException implements Exception
{
    /** @var DOMDocument */
    private $doc;

    private function __construct(DOMDocument $doc, string $message)
    {
        $this->doc = $doc;

        parent::__construct($message);
    }

    public static function fromDom(DOMDocument $doc): self
    {
        return new self($doc, 'Cannot convert empty ' . DOMDocument::class);
    }

    public function getDoc(): DOMDocument
    {
        return $this->doc;
    }
}
