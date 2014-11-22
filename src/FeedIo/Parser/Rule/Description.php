<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26/10/14
 * Time: 00:26
 */

namespace FeedIo\Parser\Rule;


use FeedIo\Feed\ItemInterface;
use FeedIo\Parser\RuleAbstract;

class Description extends RuleAbstract
{

    const NODE_NAME = 'description';

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return $this
     */
    public function set(ItemInterface $item, \DOMElement $element)
    {
        $item->setDescription($element->nodeValue);

        return $this;
    }

}