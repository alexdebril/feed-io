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

use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Feed\ItemInterface;
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
     * @param string $name
     * @return $this
     */
    public function setUrlAttributeName($name)
    {
        $this->urlAttributeName = $name;
        
        return $this;
    }

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return $this
     */
    public function setProperty(ItemInterface $item, \DOMElement $element)
    {
        $media = $item->newMedia();
        $media->setType($this->getAttributeValue($element, 'type'))
              ->setUrl($this->getAttributeValue($element, $this->getUrlAttributeName()))
              ->setLenght($this->getAttributeValue($element, 'lenght'));
              
        $item->addMedia($media);

        return $this;
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param \DomDocument $document
     * @param ItemInterface $item
     * @return \DomElement
     */
    public function createElement(\DomDocument $document, ItemInterface $item)
    {
        foreach ( $item->getMedias() as $media ) {        
            return $this->createMediaElement($document, $media);
        }

        return null;
    }

    /**
     * @param \DomDocument $document
     * @param MediaInterface $media
     * @return \DomElement
     */
    public function createMediaElement(\DomDocument $document, MediaInterface $media)
    {
        $element = $document->createElement($this->getNodeName());
        $element->setAttribute($this->getUrlAttributeName(), $media->getUrl());
        $element->setAttribute('type', $media->getType());
        $element->setAttribute('lenght', $media->getLenght());
        
        return $element;
    }

}
