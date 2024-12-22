<?php

declare(strict_types=1);

namespace FeedIo\Rule\Atom;

use FeedIo\Feed\NodeInterface;
use FeedIo\Rule\Link as BaseLink;

class Link extends BaseLink
{
    public const NODE_NAME = 'link';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element): void
    {
        if ($element->hasAttribute('href')) {
            $this->selectAlternateLink($node, $element);
        }
    }

    protected function selectAlternateLink(NodeInterface $node, \DOMElement $element): void
    {
        if (
        ($element->hasAttribute('rel') && $element->getAttribute('rel') == 'alternate')
        || is_null($node->getLink())
        ) {
            $href = $element->getAttribute('href');
            if (parse_url($href, PHP_URL_HOST) == null) {
                $href = $node->getHostFromLink(). $href;
            }
            $node->setLink($href);
        }
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node): void
    {
        $element = $document->createElement(static::NODE_NAME);
        $element->setAttribute('href', $node->getLink());

        $rootElement->appendChild($element);
    }
}
