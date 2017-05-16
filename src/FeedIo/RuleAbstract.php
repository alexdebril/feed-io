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

use FeedIo\Feed\NodeInterface;

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
        $this->nodeName = is_null($nodeName) ? static::NODE_NAME : $nodeName;
    }

    /**
     * @return string
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * @param  \DOMElement $element
     * @param  string      $name
     * @return string|null
     */
    public function getAttributeValue(\DOMElement $element, $name)
    {
        if ($element->hasAttribute($name)) {
            return $element->getAttribute($name);
        }

        return;
    }

    /**
     * @param  \DOMElement $element
     * @param  string      $name
     * @return string|null
     */
    public function getChildValue(\DOMElement $element, $name)
    {
        $list = $element->getElementsByTagName($name);
        if ( $list->length > 0 ) {
            return $list->item(0)->nodeValue;
        }

        return;
    }

    /**
     * Sets the accurate $item property according to the DomElement content
     *
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     * @return mixed
     */
    abstract public function setProperty(NodeInterface $node, \DOMElement $element);

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param  \DomDocument  $document
     * @param  NodeInterface $node
     * @return \DomElement
     */
    abstract public function createElement(\DomDocument $document, NodeInterface $node);
}
