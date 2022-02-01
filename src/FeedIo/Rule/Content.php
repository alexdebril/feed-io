<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use DomDocument;
use DOMElement;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;

class Content extends TextAbstract
{
    public const NODE_NAME = 'description';

    /**
     * @param  NodeInterface $node
     * @param  DOMElement   $element
     */
    public function setProperty(NodeInterface $node, DOMElement $element): void
    {
        if ($node instanceof ItemInterface) {
            $node->setContent($this->getProcessedContent($element, $node));
        }
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node): bool
    {
        if ($node instanceof ItemInterface) {
            return !! $node->getContent();
        }
        return false;
    }

    protected function addElement(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        if ($node instanceof ItemInterface) {
            $element = $this->generateElement($document, $node->getContent());
            $rootElement->appendChild($element);
        }
    }
}
