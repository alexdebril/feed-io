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
        $element = $node->newElement();
        $element->setName($domElement->nodeName);
        $element->setValue($domElement->nodeValue);
        foreach($domElement->attributes as $attribute) {
            $element->setAttribute($attribute->name, $attribute->value);
        }
        $node->addElement($element);

        return $this;
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

        return $domElement;
    }
}
