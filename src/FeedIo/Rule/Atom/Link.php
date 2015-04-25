<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 15:50
 */

namespace FeedIo\Rule\Atom;


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
        if ( $element->hasAttribute('href') ) {
            $item->setLink($element->getAttribute('href'));
        }

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
        $element = $document->createElement(static::NODE_NAME);
        $element->setAttribute('href', $item->getLink());

        return $element;
    }

}
