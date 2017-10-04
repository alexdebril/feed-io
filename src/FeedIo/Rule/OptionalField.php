<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use FeedIo\Feed\ElementsAwareInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\Feed\Node\ElementInterface;
use FeedIo\RuleAbstract;

class OptionalField extends RuleAbstract
{
    const NODE_NAME = 'default';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $domElement
     */
    public function setProperty(NodeInterface $node, \DOMElement $domElement) : void
    {
        $element = $this->createElementFromDomNode($node, $domElement);

        $node->addElement($element);
    }

    /**
     * @param NodeInterface $node
     * @param ElementInterface $element
     * @param \DOMNode $domNode
     */
    private function addSubElements(NodeInterface $node, ElementInterface $element, \DOMNode $domNode) : void
    {
        if (!$domNode->hasChildNodes() || !$this->hasSubElements($domNode)) {
            // no elements to add
            return;
        }

        $this->addElementsFromNodeList($node, $element, $domNode->childNodes);
    }

    /**
     * @param NodeInterface $node
     * @param ElementInterface $element
     * @param \DOMNodeList $childNodeList
     */
    private function addElementsFromNodeList(NodeInterface $node, ElementInterface $element, \DOMNodeList $childNodeList) : void
    {
        foreach ($childNodeList as $childNode) {
            if ($childNode instanceof \DOMText) {
                continue;
            }

            $element->addElement($this->createElementFromDomNode($node, $childNode));
        }
    }

    /**
     * @param \DOMNode $domNode
     * @return bool
     */
    private function hasSubElements(\DOMNode $domNode) : bool
    {
        foreach ($domNode->childNodes as $childDomNode) {
            if (!$childDomNode instanceof \DOMText) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param NodeInterface $node
     * @param \DOMNode $domNode
     * @return ElementInterface
     */
    private function createElementFromDomNode(NodeInterface $node, \DOMNode $domNode) : ElementInterface
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

    /**
     * @param \DomElement $domElement
     * @param ElementInterface $element
     * @return \DomElement
     */
    public function buildDomElement(\DomElement $domElement, ElementInterface $element) : \DOMElement
    {
        $domElement->nodeValue = $element->getValue();

        foreach ($element->getAttributes() as $name => $value) {
            $domElement->setAttribute($name, $value);
        }

        /** @var ElementInterface $subElement */
        foreach ($element->getAllElements() as $subElement) {
            $subDomElement = $domElement->ownerDocument->createElement($subElement->getName());
            $this->buildDomElement($subDomElement, $subElement);
            $domElement->appendChild($subDomElement);
        }

        return $domElement;
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node) : bool
    {
        return $node instanceof ElementsAwareInterface;
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        $domElement = $document->createElement($this->getNodeName());
        if ($node instanceof ElementsAwareInterface) {
            foreach ($node->getElementIterator($this->getNodeName()) as $element) {
                $this->buildDomElement($domElement, $element);
            }
        }

        $rootElement->appendChild($domElement);
    }
}
