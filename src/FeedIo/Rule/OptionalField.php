<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use FeedIo\Feed\NodeInterface;
use FeedIo\Feed\Node\ElementInterface;
use FeedIo\RuleAbstract;

class OptionalField extends RuleAbstract
{

    const NODE_NAME = 'default';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $domElement
     * @return $this
     */
    public function setProperty(NodeInterface $node, \DOMElement $domElement)
    {
        $element = $this->createElementFromDomNode($node, $domElement);

        $node->addElement($element);

        return $this;
    }

    /**
     * @param NodeInterface $node
     * @param ElementInterface $element
     * @param \DOMNode $domNode
     */
    private function addSubElements(NodeInterface $node, ElementInterface $element, \DOMNode $domNode)
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
    private function addElementsFromNodeList(NodeInterface $node, ElementInterface $element, \DOMNodeList $childNodeList)
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
    private function hasSubElements(\DOMNode $domNode)
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
    private function createElementFromDomNode(NodeInterface $node, \DOMNode $domNode)
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
     * creates the accurate DomElement content according to the $item's property
     *
     * @param  \DomDocument  $document
     * @param  NodeInterface $node
     * @return \DomElement
     */
    public function createElement(\DomDocument $document, NodeInterface $node)
    {
        $domElement = $document->createElement($this->getNodeName());
        foreach ($node->getElementIterator($this->getNodeName()) as $element) {
            $this->buildDomElement($domElement, $element);
        }

        return $domElement;
    }

    public function buildDomElement(\DomElement $domElement, ElementInterface $element)
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
}
