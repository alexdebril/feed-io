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
use FeedIo\RuleAbstract;

class OptionalField extends RuleAbstract
{

    const NODE_NAME = 'default';

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return $this
     */
    public function setProperty(ItemInterface $item, \DOMElement $element)
    {
        $item->getOptionalFields()->set($element->nodeName, $element->nodeValue);

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
        $element = $document->createElement($item);
        if ( $item->getOptionalFields()->has($this->getNodeName()) ) {
            $element->nodeValue = $item->getOptionalFields()->get($this->getNodeName());
        }

        return $element;
    }


}
