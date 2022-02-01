<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use InvalidArgumentException;
use FeedIo\Feed\NodeInterface;
use FeedIo\DateRuleAbstract;

class ModifiedSince extends DateRuleAbstract
{
    public const NODE_NAME = 'pubDate';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element): void
    {
        $node->setLastModified($this->getDateTimeBuilder()->convertToDateTime($element->nodeValue));
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node): void
    {
        $date = is_null($node->getLastModified()) ? new \DateTime() : $node->getLastModified();

        $rootElement->appendChild($document->createElement(
            $this->getNodeName(),
            $date->format($this->getDefaultFormat())
        ));
    }
}
