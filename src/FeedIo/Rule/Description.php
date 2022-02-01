<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use DomDocument;
use DOMElement;
use FeedIo\Feed\NodeInterface;
use FeedIo\FeedInterface;

class Description extends TextAbstract
{
    public const NODE_NAME = 'description';

    /**
     * @param  NodeInterface $node
     * @param  DOMElement   $element
     */
    public function setProperty(NodeInterface $node, DOMElement $element): void
    {
        if ($node instanceof FeedInterface) {
            $node->setDescription($this->getProcessedContent($element, $node));
        }
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node): bool
    {
        if ($node instanceof FeedInterface) {
            return !! $node->getDescription();
        }
        return false;
    }

    protected function addElement(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        if ($node instanceof FeedInterface) {
            $element = $this->generateElement($document, $node->getDescription());
            $rootElement->appendChild($element);
        }
    }
}
