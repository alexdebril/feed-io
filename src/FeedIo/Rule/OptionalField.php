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


use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\Item\ElementInterface;
use FeedIo\RuleAbstract;

class OptionalField extends RuleAbstract
{

    const NODE_NAME = 'default';

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return $this
     */
    public function setProperty(ItemInterface $item, \DOMElement $domElement)
    {
        $element = $item->newElement();
        $element->setName($domElement->nodeName);
        $element->setValue($domElement->nodeValue);
        $item->addElement($element);

        return $this;
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param \DomDocument $document
     * @param ItemInterface $item
     * @return \DomElement
     */
    public function createElement(\DomDocument $document, ItemInterface $item)
    {
       $domElement = $document->createElement($this->getNodeName());
       
        foreach ( $item->getElementIterator($this->getNodeName()) as $element) {
            $this->buildDomElement($domElement, $element);
        }

        return $domElement;
    }

    public function buildDomElement(\DomElement $domElement, ElementInterface $element)
    {
        $domElement->nodeValue = $element->getValue();
        
        foreach ( $element->getAttributes() as $name => $value ) {
            $domElement->setAttribute($name, $value);
        }
        
        return $domElement;
    }

}
