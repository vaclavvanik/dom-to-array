<?php

declare(strict_types=1);

namespace VaclavVanik\DomToArray;

class DomOptions
{
    /** @var bool */
    private $skipAttributes;

    /** @var bool */
    private $keepMixedContent;

    /** @var bool */
    private $omitRootElement;

    /** @var bool */
    private $useAttributeNodeName;

    public const SKIP_ATTRIBUTES = 'skip_attributes';

    public const KEEP_MIXED_CONTENT = 'keep_mixed_content';

    public const OMIT_ROOT_ELEMENT = 'omit_root_element';

    public const USE_ATTRIBUTE_NODE_NAME = 'use_attribute_node_name';

    private function __construct(
        bool $skipAttributes,
        bool $keepMixedContent,
        bool $omitRootElement,
        bool $useAttributeNodeName
    ) {
        $this->skipAttributes = $skipAttributes;
        $this->keepMixedContent = $keepMixedContent;
        $this->omitRootElement = $omitRootElement;
        $this->useAttributeNodeName = $useAttributeNodeName;
    }

    /** @param array{skip_attributes?: bool, keep_mixed_content?: bool, omit_root_element?: bool, use_attribute_node_name?: bool} $array */
    public static function fromArray(array $array): self
    {
        $skipAttributes = (bool) ($array[self::SKIP_ATTRIBUTES] ?? false);
        $keepMixedContent = (bool) ($array[self::KEEP_MIXED_CONTENT] ?? false);
        $omitRootElement = (bool) ($array[self::OMIT_ROOT_ELEMENT] ?? false);
        $useAttributeNodeName = (bool) ($array[self::USE_ATTRIBUTE_NODE_NAME] ?? false);

        return new self($skipAttributes, $keepMixedContent, $omitRootElement, $useAttributeNodeName);
    }

    public function getSkipAttributes(): bool
    {
        return $this->skipAttributes;
    }

    public function getKeepMixedContent(): bool
    {
        return $this->keepMixedContent;
    }

    public function getOmitRootElement(): bool
    {
        return $this->omitRootElement;
    }

    public function getUseAttributeNodeName(): bool
    {
        return $this->useAttributeNodeName;
    }
}
