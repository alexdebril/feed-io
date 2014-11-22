<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 11:24
 */

namespace FeedIo\Parser\Rule;


use FeedIo\Feed\ItemInterface;
use FeedIo\Parser\RuleAbstract;

class PublicId extends RuleAbstract
{
    const NODE_NAME = 'guid';

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return mixed
     */
    public function set(ItemInterface $item, \DOMElement $element)
    {
        $item->setPublicId($element->nodeValue);

        return $item;
    }

}