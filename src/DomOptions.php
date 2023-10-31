<?php

declare(strict_types=1);

namespace VaclavVanik\DomToArray;

class DomOptions
{
    /** @var bool */
    private $skipAttributes;

    public const SKIP_ATTRIBUTES = 'skip_attributes';

    private function __construct(bool $skipAttributes)
    {
        $this->skipAttributes = $skipAttributes;
    }

    /** @param array{skip_attributes?: bool} $array */
    public static function fromArray(array $array): self
    {
        $skipAttributes = $array[self::SKIP_ATTRIBUTES] ?? false;

        return new self($skipAttributes);
    }

    public function getSkipAttributes(): bool
    {
        return $this->skipAttributes;
    }
}
