<?php

declare(strict_types=1);

namespace FeedIo\Rule\Atom;

use DOMDocument;
use DOMElement;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\Rule\TextAbstract;

class Summary extends TextAbstract
{
    public const NODE_NAME = 'summary';

    public function setProperty(NodeInterface $node, DOMElement $element): void
    {
        if ($node instanceof ItemInterface) {
            $node->setSummary(
                $this->getProcessedContent($element, $node)
            );
        }
    }

    protected function addElement(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        if ($node instanceof ItemInterface) {
            $rootElement->appendChild(
                $this->generateElement($document, $node->getSummary())
            );
        }
    }

    protected function hasValue(NodeInterface $node): bool
    {
        if ($node instanceof ItemInterface) {
            return !! $node->getSummary();
        }

        return false;
    }
}
