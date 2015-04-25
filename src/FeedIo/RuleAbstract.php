<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;


use FeedIo\Feed\ItemInterface;

abstract class RuleAbstract
{
    const NODE_NAME = 'node';

    /**
     * @var string
     */
    protected $nodeName;

    /**
     * @param string $nodeName
     */
    public function __construct($nodeName = null)
    {
        $this->nodeName = is_null($nodeName) ? static::NODE_NAME:$nodeName;
    }

    /**
     * @return string
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * @param \DOMElement $element
     * @param string $name
     * @return string|null
     */
    public function getAttributeValue(\DOMElement $element, $name)
    {
        if ( $element->hasAttribute($name) ) {
            return $element->getAttribute($name);
        }
        
        return null;
    }

    /**
     * Sets the accurate $item property according to the DomElement content
     *
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return mixed
     */
    abstract public function setProperty(ItemInterface $item, \DOMElement $element);

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param \DomDocument $document
     * @param ItemInterface $item
     * @return \DomElement
     */
    abstract public function createElement(\DomDocument $document, ItemInterface $item);

}
