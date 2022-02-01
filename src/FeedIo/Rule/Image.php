<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Image extends RuleAbstract
{
    public const NODE_NAME = 'image';

    public function setProperty(NodeInterface $node, \DOMElement $element): void
    {
        if ($node instanceof ItemInterface) {
            $media = new \FeedIo\Feed\Item\Media();
            $media->setUrl($element->textContent);
            $node->addMedia($media);
        }
    }

    protected function hasValue(NodeInterface $node): bool
    {
        return false;
    }

    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node): void
    {
        throw new \RuntimeException("you should not try to write a <image> tag");
    }
}
