<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 11:24
 */

namespace FeedIo\Rule;


use FeedIo\Feed\ItemInterface;
use FeedIo\RuleAbstract;

class PublicId extends RuleAbstract
{
    const NODE_NAME = 'guid';

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return mixed
     */
    public function setFromElement(ItemInterface $item, \DOMElement $element)
    {
        $item->setPublicId($element->nodeValue);

        return $item;
    }

}