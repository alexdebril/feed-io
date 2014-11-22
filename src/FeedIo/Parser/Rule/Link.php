<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 31/10/14
 * Time: 12:02
 */

namespace FeedIo\Parser\Rule;


use FeedIo\Feed\ItemInterface;
use FeedIo\Parser\RuleAbstract;

class Link extends RuleAbstract
{
    const NODE_NAME = 'link';

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return mixed
     */
    public function set(ItemInterface $item, \DOMElement $element)
    {
        $item->setLink($element->nodeValue);

        return $this;
    }

}