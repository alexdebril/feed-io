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


use FeedIo\Feed\Item\Element;
use FeedIo\Feed\Item\ElementIterator;
use FeedIo\Feed\Item\ElementInterface;

class Item extends Node implements ItemInterface
{
    
    /**
     * @var \ArrayIterator
     */
    protected $elements;

    public function __construct()
    {
        $this->elements = new \ArrayIterator;
    }

    /**
     * @param string $name element name
     * @param string $value element value
     * @return $this
     */        
    public function set($name, $value)
    {
        $element = $this->newElement();
        
        $element->setName($name);
        $element->setValue($value);
        
        $this->addElement($element);
        
        return $this;
    }

    /**
     * @return ElementInterface
     */
    public function newElement()
    {
        return new Element;
    }

    /**
     * @param string $name element name
     * @return ElementIterator
     */
    public function getValue($name)
    {
        foreach ( $this->getElementIterator($name) as $element ) {
            return $element->getValue();
        }
        
        return null;
    }
    
    /**
     * @param string $name element name
     * @return ElementIterator
     */
    public function getElementIterator($name)
    {
        return new ElementIterator($this->elements, $name);
    }

    /**
     * @param string $name element name
     * @return boolean true if the element exists
     */   
    public function hasElement($name)
    {
        $filter = $this->getElementIterator($name);
        
        return $filter->count() > 0;
    }
    
    /**
     * @param Element $element
     * @return $this
     */
    public function addElement(ElementInterface $element)
    {
        $this->elements->append($element);
    
        return $this;
    }

    /**
     * Returns all the item's optional elements
     * @return \ArrayIterator
     */
    public function getAllElements()
    {
        return $this->elements;
    }

    /**
     * Returns the item's optional elements tag names
     * @return array
     */
    public function listElements()
    {
        $out = array();
        foreach ( $this->elements as $element ) {
            $out[] = $element->getName();
        }
        
        return $out;
    }

}
