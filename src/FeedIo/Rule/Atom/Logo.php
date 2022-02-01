<?php

declare(strict_types=1);

namespace FeedIo\Rule\Atom;

use FeedIo\Feed\Item;
use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;
use FeedIo\FeedInterface;

class Logo extends RuleAbstract
{
    // https://tools.ietf.org/html/rfc4287#section-4.2.8
    public const NODE_NAME = 'logo';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element): void
    {
        if ($node instanceof FeedInterface) {
            $node->setLogo($element->nodeValue);
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
        if (!($node instanceof FeedInterface) || is_null($node->getLogo())) {
            return;
        }
        $rootElement->appendChild($document->createElement($this->getNodeName(), $node->getLogo()));
    }
}
