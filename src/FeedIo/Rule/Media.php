<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use FeedIo\Feed\Item;
use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Media extends RuleAbstract
{
    const NODE_NAME = 'enclosure';

    protected $urlAttributeName = 'url';

    /**
     * @return string
     */
    public function getUrlAttributeName() : string
    {
        return $this->urlAttributeName;
    }

    /**
     * @param  string $name
     */
    public function setUrlAttributeName(string $name) : void
    {
        $this->urlAttributeName = $name;
    }

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element) : void
    {
        if ($node instanceof ItemInterface) {
            $media = $node->newMedia();
            $media->setNodeName($element->nodeName)
                ->setType($this->getAttributeValue($element, 'type'))
                ->setUrl($this->getAttributeValue($element, $this->getUrlAttributeName()))
                ->setLength($this->getAttributeValue($element, 'length'));

            $node->addMedia($media);
        }
    }

    /**
     * @param  \DomDocument   $document
     * @param  MediaInterface $media
     * @return \DomElement
     */
    public function createMediaElement(\DomDocument $document, MediaInterface $media) : \DOMElement
    {
        $element = $document->createElement($this->getNodeName());
        $element->setAttribute($this->getUrlAttributeName(), $media->getUrl());
        $element->setAttribute('type', $media->getType() ?? '');
        $element->setAttribute('length', $media->getLength() ?? '');

        return $element;
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node) : bool
    {
        return $node instanceof ItemInterface && !! $node->getMedias();
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        foreach ($node->getMedias() as $media) {
            $rootElement->appendChild($this->createMediaElement($document, $media));
        }
    }
}
