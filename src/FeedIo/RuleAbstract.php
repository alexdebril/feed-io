<?php

declare(strict_types=1);

namespace FeedIo;

use DOMDocument;
use DOMElement;
use FeedIo\Feed\NodeInterface;

abstract class RuleAbstract
{
    public const NODE_NAME = 'node';

    protected string $nodeName;

    public function __construct(string $nodeName = null)
    {
        $this->nodeName = $nodeName ?? static::NODE_NAME;
    }

    public function getNodeName(): string
    {
        return $this->nodeName;
    }

    public function getAttributeValue(DOMElement $element, string $name): ?string
    {
        if ($element->hasAttribute($name)) {
            return $element->getAttribute($name);
        }

        return null;
    }

    public function getChildValue(DOMElement $element, string $name, ?string $ns = null): ?string
    {
        if ($ns === null) {
            $list = $element->getElementsByTagName($name);
        } else {
            $list = $element->getElementsByTagNameNS($ns, $name);
        }
        if ($list->length > 0) {
            return $list->item(0)->nodeValue;
        }

        return null;
    }

    public function getChildAttributeValue(DOMElement $element, string $child_name, string $attribute_name, ?string $ns = null): ?string
    {
        if ($ns === null) {
            $list = $element->getElementsByTagName($child_name);
        } else {
            $list = $element->getElementsByTagNameNS($ns, $child_name);
        }
        if ($list->length > 0) {
            return $list->item(0)->getAttribute($attribute_name);
        }

        return null;
    }

    public function apply(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        if ($this->hasValue($node)) {
            $this->addElement($document, $rootElement, $node);
        }
    }

    protected function setNonEmptyAttribute(DomElement $element, string $name, string $value = null): void
    {
        if (! is_null($value)) {
            $element->setAttribute($name, $value);
        }
    }

    protected function appendNonEmptyChild(DomDocument $document, DOMElement $element, string $name, string $value = null): void
    {
        if (! is_null($value)) {
            $element->appendChild($document->createElement($name, $value));
        }
    }

    abstract public function setProperty(NodeInterface $node, DOMElement $element): void;

    abstract protected function hasValue(NodeInterface $node): bool;

    abstract protected function addElement(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void;
}
