<?php

declare(strict_types=1);

namespace VaclavVanik\DomToArray;

use DOMCdataSection;
use DOMDocument;
use DOMElement;
use DOMText;

use function count;
use function trim;

class DomToArray
{
    /** @var DOMDocument */
    private $doc;

    private const KEY_ATTRIBUTES = '@attributes';

    private const KEY_VALUE = '@value';

    private function __construct(DOMDocument $doc)
    {
        $this->doc = $doc;
    }

    /** @return array<mixed> */
    public static function toArray(DOMDocument $doc): array
    {
        return (new self($doc))->convert();
    }

    /** @return array<mixed> */
    private function convert(): array
    {
        return [
            $this->doc->documentElement->nodeName => $this->convertDomElement($this->doc->documentElement),
        ];
    }

    /** @return array<mixed> */
    private function convertDomAttributes(DOMElement $element): array
    {
        if ($element->hasAttributes()) {
            $attributes = [];

            foreach ($element->attributes as $attr) {
                $attributes[$attr->name] = $attr->value;
            }

            return [self::KEY_ATTRIBUTES => $attributes];
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

    /** @return array<mixed>|string */
    private function convertDomElement(DOMElement $element)
    {
        $result = $this->convertDomAttributes($element);

        $childNames = $this->childNamesCount($element);

        $isArrayElement = static function (string $name) use ($childNames): bool {
            return $childNames[$name] > 1;
        };

        foreach ($element->childNodes as $childNode) {
            if ($childNode instanceof DOMCdataSection) {
                $result[self::KEY_VALUE] = $childNode->data;
                continue;
            }

            if ($childNode instanceof DOMText) {
                $result[self::KEY_VALUE] = $childNode->textContent;
                continue;
            }

            if (! ($childNode instanceof DOMElement)) {
                continue;
            }

            if ($isArrayElement($childNode->nodeName)) {
                if (! isset($result[$childNode->nodeName])) {
                    $result[$childNode->nodeName] = [];
                }

                $result[$childNode->nodeName][] = $this->convertDomElement($childNode);
                continue;
            }

            $result[$childNode->nodeName] = $this->convertDomElement($childNode);
        }

        if (isset($result[self::KEY_VALUE]) && trim($result[self::KEY_VALUE]) !== '') {
            return $result[self::KEY_VALUE];
        }

        unset($result[self::KEY_VALUE]);

        return count($result) > 0 ? $result : '';
    }
}
