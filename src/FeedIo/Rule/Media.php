<?php
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
    public function getUrlAttributeName()
    {
        return $this->urlAttributeName;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function setUrlAttributeName($name)
    {
        $this->urlAttributeName = $name;

        return $this;
    }

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     * @return $this
     */
    public function setProperty(NodeInterface $node, \DOMElement $element)
    {
        if ($node instanceof ItemInterface) {
            $media = $node->newMedia();
            $media->setType($this->getAttributeValue($element, 'type'))
                ->setUrl($this->getAttributeValue($element, $this->getUrlAttributeName()))
                ->setLength($this->getAttributeValue($element, 'length'));

            $node->addMedia($media);
        }

        return $this;
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param  \DomDocument  $document
     * @param  NodeInterface $node
     * @return \DomElement
     */
    public function createElement(\DomDocument $document, NodeInterface $node)
    {
        if ($node instanceof ItemInterface) {
            foreach ($node->getMedias() as $media) {
                return $this->createMediaElement($document, $media);
            }
        }

        return;
    }

    /**
     * @param  \DomDocument   $document
     * @param  MediaInterface $media
     * @return \DomElement
     */
    public function createMediaElement(\DomDocument $document, MediaInterface $media)
    {
        $element = $document->createElement($this->getNodeName());
        $element->setAttribute($this->getUrlAttributeName(), $media->getUrl());
        $element->setAttribute('type', $media->getType());
        $element->setAttribute('length', $media->getLength());

        return $element;
    }
}
