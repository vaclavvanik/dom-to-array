<?php

declare(strict_types=1);

namespace VaclavVanik\DomToArray;

class DomOptions
{
    /** @var bool */
    private $skipAttributes;

    /** @var bool */
    private $keepMixedContent;

    public const SKIP_ATTRIBUTES = 'skip_attributes';

    public const KEEP_MIXED_CONTENT = 'keep_mixed_content';

    private function __construct(bool $skipAttributes, bool $keepMixedContent)
    {
        $this->skipAttributes = $skipAttributes;
        $this->keepMixedContent = $keepMixedContent;
    }

    /** @param array{skip_attributes?: bool, keep_mixed_content?: bool} $array */
    public static function fromArray(array $array): self
    {
        $skipAttributes = (bool) ($array[self::SKIP_ATTRIBUTES] ?? false);
        $keepMixedContent = (bool) ($array[self::KEEP_MIXED_CONTENT] ?? false);

        return new self($skipAttributes, $keepMixedContent);
    }

    public function getSkipAttributes(): bool
    {
        return $this->skipAttributes;
    }

    public function getKeepMixedContent(): bool
    {
        return $this->keepMixedContent;
    }
}
