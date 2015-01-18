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
use FeedIo\DateRuleAbstract;

class ModifiedSince extends DateRuleAbstract
{
    const NODE_NAME = 'pubDate';

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return $this
     */
    public function setProperty(ItemInterface $item, \DOMElement $element)
    {
        $item->setLastModified($this->getDateTimeBuilder()->convertToDateTime($element->nodeValue));

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
        return $document->createElement(
            $this->getNodeName(),
            $item->getLastModified()->format($this->getDefaultFormat())
        );
    }


}
