<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31/10/14
 * Time: 12:02
 */

namespace FeedIo\Rule;


use FeedIo\Feed\ItemInterface;
use FeedIo\RuleAbstract;

class Link extends RuleAbstract
{
    const NODE_NAME = 'link';

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return mixed
     */
    public function setProperty(ItemInterface $item, \DOMElement $element)
    {
        $item->setLink($element->nodeValue);

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
        return $document->createElement($this->getNodeName(), $item->getLink());
    }

}
