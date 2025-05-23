<?php

declare(strict_types=1);

namespace VaclavVanik\DomToArray;

use DOMCdataSection;
use DOMDocument;
use DOMElement;
use DOMText;

use function array_merge;
use function count;
use function is_array;
use function is_string;
use function trim;

class DomToArray
{
    /** @var DOMDocument */
    private $doc;

    /** @var DomOptions|null */
    private $options;

    private const ATTRIBUTE_PREFIX = '@';

    private const KEY_VALUE = '@value';

    private function __construct(DOMDocument $doc, ?DomOptions $options)
    {
        $this->doc = $doc;
        $this->options = $options;
    }

    /** @return array<mixed> */
    public static function toArray(DOMDocument $doc): array
    {
        return (new self($doc, null))->convert();
    }

    /** @return array<mixed> */
    public static function toArrayWithOptions(DOMDocument $doc, DomOptions $options): array
    {
        return (new self($doc, $options))->convert();
    }

    private function skipAttributes(): bool
    {
        if ($this->options) {
            return $this->options->getSkipAttributes();
        }

        return false;
    }

    /** @return array<mixed> */
    private function convert(): array
    {
        $element = $this->doc->documentElement;

        if ($element === null) {
            return [];
        }

        $result[$element->nodeName] = $this->convertDomElement($element);

        return $this->mergeAttributes($result, $this->convertDomAttributes($element));
    }

    /** @return array<mixed> */
    private function convertDomAttributes(DOMElement $element): array
    {
        if ($this->skipAttributes() === true) {
            return [];
        }

        if ($element->hasAttributes()) {
            $attributes = [];

            foreach ($element->attributes as $attr) {
                $attributes[$element->nodeName . self::ATTRIBUTE_PREFIX . $attr->name] = $attr->value;
            }

            return $attributes;
        }

        return [];
    }

    /** @return array<string,int> */
    private function childNamesCount(DOMElement $element): array
    {
        $names = [];

        foreach ($element->childNodes as $childNode) {
            if (! ($childNode instanceof DOMElement)) {
                continue;
            }

            if (! isset($names[$childNode->nodeName])) {
                $names[$childNode->nodeName] = 0;
            }

            ++$names[$childNode->nodeName];
        }

        return $names;
    }

    /** @param array<string,int> $childNamesCount */
    private function isArrayElement(string $name, array $childNamesCount): bool
    {
        return $childNamesCount[$name] > 1;
    }

    /**
     * @param array<mixed> $result
     * @param array<mixed> $attributes
     *
     * @return array<mixed>
     */
    private function mergeAttributes(array $result, array $attributes): array
    {
        if (count($attributes) > 0) {
            return array_merge($result, $attributes);
        }

        return $result;
    }

    /** @return array<mixed>|string */
    private function convertDomElement(DOMElement $element) /*: string|array*/
    {
        $result = [];

        $childNamesCount = $this->childNamesCount($element);

        foreach ($element->childNodes as $childNode) {
            if ($childNode instanceof DOMCdataSection) {
                $result[self::KEY_VALUE] = $childNode->data;
                continue;
            }

            if ($childNode instanceof DOMText) {
                $result[self::KEY_VALUE] = $childNode->textContent;
                continue;
            }

            if ($childNode instanceof DOMComment) {
                continue;
            }

            if ($this->isArrayElement($childNode->nodeName, $childNamesCount)) {
                $childResult = $this->convertDomElement($childNode);

                if ($childResult === '') {
                    $childResult = [];
                }

                if (is_string($childResult) && $childNode->hasAttributes() && $this->skipAttributes() === false) {
                    $childResult = [$childNode->nodeName => $childResult];

                    $result[] = $this->mergeAttributes($childResult, $this->convertDomAttributes($childNode));
                    continue;
                }

                if (! isset($result[$childNode->nodeName])) {
                    $result[$childNode->nodeName] = [];
                }

                if (is_array($childResult)) {
                    $childResult = $this->mergeAttributes($childResult, $this->convertDomAttributes($childNode));
                }

                $result[$childNode->nodeName][] = $childResult === [] ? '' : $childResult;
                continue;
            }

            $result[$childNode->nodeName] = $this->convertDomElement($childNode);
            $result = $this->mergeAttributes($result, $this->convertDomAttributes($childNode));
        }

        if (isset($result[self::KEY_VALUE]) && trim($result[self::KEY_VALUE]) !== '') {
            return $result[self::KEY_VALUE];
        }

        unset($result[self::KEY_VALUE]);

        return count($result) > 0 ? $result : '';
    }
}
