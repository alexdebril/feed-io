<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Link extends RuleAbstract
{
    public const NODE_NAME = 'link';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element): void
    {
        $nodeValue = $element->nodeValue;
        if (parse_url($nodeValue, PHP_URL_HOST) == null) {
            $nodeValue = $node->getHostFromLink(). $nodeValue;
        }
        $node->setLink($nodeValue);
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node): bool
    {
        return !! $node->getLink();
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node): void
    {
        $rootElement->appendChild($document->createElement($this->getNodeName(), $node->getLink()));
    }
}
