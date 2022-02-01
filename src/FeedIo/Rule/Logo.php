<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use FeedIo\Feed\Item;
use FeedIo\FeedInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Logo extends RuleAbstract
{
    public const NODE_NAME = 'image';

    protected $urlAttributeName = 'url';

    /**
     * @return string
     */
    public function getUrlAttributeName(): string
    {
        return $this->urlAttributeName;
    }

    /**
     * @param  string $name
     */
    public function setUrlAttributeName(string $name): void
    {
        $this->urlAttributeName = $name;
    }

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element): void
    {
        if ($node instanceof FeedInterface) {
            for ($i = $element->childNodes->length; --$i >= 0;) {
                $child = $element->childNodes->item($i);
                if ($child instanceof \DOMElement && $child->tagName === $this->getUrlAttributeName()) {
                    $node->setLogo($child->textContent);
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node): bool
    {
        return $node instanceof FeedInterface && !! $node->getLogo();
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node): void
    {
        if ($node instanceof FeedInterface) {
            $element = $document->createElement(static::NODE_NAME);
            $this->appendNonEmptyChild($document, $element, 'url', $node->getLogo());
            $this->appendNonEmptyChild($document, $element, 'title', $node->getTitle());
            $this->appendNonEmptyChild($document, $element, 'link', $node->getLink());

            $rootElement->appendChild($element);
        }
    }
}
