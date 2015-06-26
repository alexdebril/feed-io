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

use FeedIo\Feed\NodeInterface;
use FeedIo\DateRuleAbstract;

class ModifiedSince extends DateRuleAbstract
{
    const NODE_NAME = 'pubDate';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     * @return $this
     */
    public function setProperty(NodeInterface $node, \DOMElement $element)
    {
        $node->setLastModified($this->getDateTimeBuilder()->convertToDateTime($element->nodeValue));

        return $this;
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param  \DomDocument  $document
     * @param  NodeInterface $node
     * @return \DomElement
     */
    public function createElement(\DomDocument $document, NodeInterface $node)
    {
        return $document->createElement(
            $this->getNodeName(),
            $node->getLastModified()->format($this->getDefaultFormat())
        );
    }
}
