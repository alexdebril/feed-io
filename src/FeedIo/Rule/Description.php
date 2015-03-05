<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26/10/14
 * Time: 00:26
 */

namespace FeedIo\Rule;


use FeedIo\Feed\ItemInterface;
use FeedIo\RuleAbstract;

class Description extends RuleAbstract
{

    const NODE_NAME = 'description';

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return $this
     */
    public function setProperty(ItemInterface $item, \DOMElement $element)
    {
        $item->setDescription($element->nodeValue);

        return $this;
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param \DOMDocument $document
     * @param ItemInterface $item
     * @return \DOMElement
     */
    public function createElement(\DOMDocument $document, ItemInterface $item)
    {
        $description = htmlspecialchars($item->getDescription());
        return $document->createElement(static::NODE_NAME, $description);
    }

}
