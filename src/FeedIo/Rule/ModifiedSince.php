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
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node) : bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        $date = is_null($node->getLastModified()) ? new \DateTime():$node->getLastModified();

        $rootElement->appendChild($document->createElement(
            $this->getNodeName(),
            $date->format($this->getDefaultFormat())
        ));
    }
}
