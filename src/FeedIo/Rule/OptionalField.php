<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use DomDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMText;
use FeedIo\Feed\ElementsAwareInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\Feed\Node\ElementInterface;
use FeedIo\RuleAbstract;

class OptionalField extends RuleAbstract
{
    public const NODE_NAME = 'default';

    public function setProperty(NodeInterface $node, DOMElement $element): void
    {
        if ($node instanceof ElementsAwareInterface) {
            $newElement = $this->createElementFromDomNode($node, $element);
            $node->addElement($newElement);
        }
    }

    private function addSubElements(ElementsAwareInterface $node, ElementInterface $element, DOMNode $domNode): void
    {
        if (!$domNode->hasChildNodes() || !$this->hasSubElements($domNode)) {
            return;
        }

        $this->addElementsFromNodeList($node, $element, $domNode->childNodes);
    }

    private function addElementsFromNodeList(ElementsAwareInterface $node, ElementInterface $element, DOMNodeList $childNodeList): void
    {
        foreach ($childNodeList as $childNode) {
            if ($childNode instanceof DOMText) {
                continue;
            }

            if ($element instanceof ElementsAwareInterface) {
                $element->addElement($this->createElementFromDomNode($node, $childNode));
            }
        }
    }

    private function hasSubElements(DOMNode $domNode): bool
    {
        foreach ($domNode->childNodes as $childDomNode) {
            if (!$childDomNode instanceof \DOMText) {
                return true;
            }
        }

        return false;
    }

    private function createElementFromDomNode(ElementsAwareInterface $node, DOMNode $domNode): ElementInterface
    {
        $element = $node->newElement();
        $element->setName($domNode->nodeName);
        $element->setValue($domNode->nodeValue);

        foreach ($domNode->attributes as $attribute) {
            $element->setAttribute($attribute->name, $attribute->value);
        }
        $this->addSubElements($node, $element, $domNode);

        return $element;
    }

    public function buildDomElement(DomElement $domElement, ElementInterface $element): DOMElement
    {
        $domElement->nodeValue = $element->getValue();

        foreach ($element->getAttributes() as $name => $value) {
            $domElement->setAttribute($name, $value);
        }

        if ($element instanceof ElementsAwareInterface) {
            /** @var ElementInterface $subElement */
            foreach ($element->getAllElements() as $subElement) {
                $subDomElement = $domElement->ownerDocument->createElement($subElement->getName());
                $this->buildDomElement($subDomElement, $subElement);
                $domElement->appendChild($subDomElement);
            }
        }

        return $domElement;
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node): bool
    {
        return $node instanceof ElementsAwareInterface;
    }

    /**
     * @inheritDoc
     */
    protected function addElement(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        $addedElementsCount = 0;

        if ($node instanceof ElementsAwareInterface) {
            foreach ($node->getElementIterator($this->getNodeName()) as $element) {
                $domElement = $document->createElement($this->getNodeName());
                $this->buildDomElement($domElement, $element);
                $rootElement->appendChild($domElement);
                $addedElementsCount++;
            }
        }

        if (! $addedElementsCount) {
            // add an implicit empty element if the node had no elements matching this rule
            $rootElement->appendChild($document->createElement($this->getNodeName()));
        }
    }
}
