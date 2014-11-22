<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 15:50
 */

namespace FeedIo\Parser\Rule\Atom;


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
        if ( $element->hasAttribute('href') ) {
            $item->setLink($element->getAttribute('href'));
        }

        return $this;
    }


}