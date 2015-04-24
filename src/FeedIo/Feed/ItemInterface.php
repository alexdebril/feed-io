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
 * Interface ItemInterface
 * Represents news items
 * each mandatory field has its own setter and getter
 * other fields are handled using set() and getValue()
 * @package FeedIo
 */
interface ItemInterface extends NodeInterface
{

    /**
     * @return \FeedIo\Feed\Item\ElementInterface
     */
    public function newElement();
    
    /**
     * @param string $name element name
     * @return mixed
     */
    public function getValue($name);
    
    /**
     * @param string $name element name
     * @param string $value element value
     * @return $this
     */      
    public function set($name, $value);
    
    /**
     * @param string $name element name
     * @return \FeedIo\Feed\Item\ElementIterator
     */
    public function getElementIterator($name);
    
    /**
     * @param string $name element name
     * @return boolean true if the element exists
     */   
    public function hasElement($name);
    
    /**
     * @param Element $element
     * @return $this
     */
    public function addElement(ElementInterface $element);

    /**
     * Returns all the item's optional elements
     * @return \ArrayIterator
     */
    public function getAllElements();

    /**
     * Returns the item's optional elements tag names
     * @return array
     */
    public function listElements();
    
    /**
     * @param MediaInterface $media
     * @return $this
     */  
    public function addMedia(MediaInterface $media);
    
    /**
     * @return \ArrayIterator
     */
    public function getMedias();
    
    /**
     * @return boolean
     */
    public function hasMedia();
    
    /**
     * @return MediaInterface
     */
    public function newMedia();
    
}
