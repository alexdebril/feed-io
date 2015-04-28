<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed;

use FeedIo\Feed\Item\ElementInterface;
use FeedIo\Feed\Item\MediaInterface;

/**
 * Describes an Item instance
 *
 * an item holds three types of properties :
 * - basic values inherited from the NodeInterface like title, description, URL
 * - MediaInterface instances for medias like videos, images and podcasts
 * - ElementInterface instances for nodes not related to a known property of the ItemInterface instance
 *
 * ElementInterface instances are accessed using two methods :
 * 
 * - ItemInterface::getElementIterator($name). Use it to read an array of elements or if you need to get an ElementInterface instance
 * - ItemInterface::getValue($name). use it to get the element's v    lue
 * 
 */
interface ItemInterface extends NodeInterface
{

    /**
     * returns a new ElementInterface
     *
     * @return \FeedIo\Feed\Item\ElementInterface
     */
    public function newElement();
    
    /**
     * returns an element's value
     *
     * @param string $name element name
     * @return mixed
     */
    public function getValue($name);
    
    /**
     * creates a new ElementInterface called $name and sets its value to $value
     *
     * @param string $name element name
     * @param string $value element value
     * @return $this
     */      
    public function set($name, $value);
    
    /**
     * returns the ElementIterator to iterate over ElementInterface instances called $name
     *
     * @param string $name element name
     * @return \FeedIo\Feed\Item\ElementIterator
     */
    public function getElementIterator($name);
    
    /**
     * returns true if an ElementInterface instance called $name exists
     *
     * @param string $name element name
     * @return boolean true if the element exists
     */   
    public function hasElement($name);
    
    /**
     * adds $element to the object's attributes
     *
     * @param Element $element
     * @return $this
     */
    public function addElement(ElementInterface $element);

    /**
     * Returns all the item's elements
     *
     * @return \ArrayIterator
     */
    public function getAllElements();

    /**
     * Returns the item's elements tag names
     *
     * @return array
     */
    public function listElements();
    
    /**
     * adds $media to the object's attributes
     *
     * @param MediaInterface $media
     * @return $this
     */  
    public function addMedia(MediaInterface $media);
    
    /**
     * returns the current object's medias
     *
     * @return \ArrayIterator
     */
    public function getMedias();
    
    /**
     * returns true if at least one MediaInterface exists in the object's attributes
     *
     * @return boolean
     */
    public function hasMedia();
    
    /**
     * returns a new MediaInterface
     *
     * @return MediaInterface
     */
    public function newMedia();
    
}
