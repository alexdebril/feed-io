<?php declare(strict_types=1);


namespace FeedIo\Rule;


use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;

class Image extends \FeedIo\RuleAbstract
{

    const NODE_NAME = 'image';

    /**
     * @inheritDoc
     */
    public function setProperty(NodeInterface $node, \DOMElement $element): void
    {
        if ($node instanceof ItemInterface) {
            $media = new \FeedIo\Feed\Item\Media();
            $media->setUrl($element->textContent);
            $node->addMedia($media);
        }
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node): void
    {
        throw new \RuntimeException("you should not try to write a <image> tag");
    }
}
