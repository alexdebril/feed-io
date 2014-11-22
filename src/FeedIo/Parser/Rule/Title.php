<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser\Rule;

use FeedIo\Feed\ItemInterface;
use FeedIo\Parser\RuleAbstract;

class Title extends RuleAbstract
{
    const NODE_NAME = 'title';

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return $this
     */
    public function set(ItemInterface $item, \DOMElement $element)
    {
        $item->setTitle($element->nodeValue);

        return $this;
    }

}
