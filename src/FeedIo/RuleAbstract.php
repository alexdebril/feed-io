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
        if ($list->length > 0) {
            return $list->item(0)->nodeValue;
        }

        return;
    }

    /**
     * adds the accurate DomElement content according to the node's property
     *
     * @param \DomDocument $document
     * @param \DOMElement $rootElement
     * @param NodeInterface $node
     */
    public function apply(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        if ($this->hasValue($node)) {
            $this->addElement($document, $rootElement, $node);
        }
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
     * Tells if the node contains the expected value
     *
     * @param NodeInterface $node
     * @return bool
     */
    abstract protected function hasValue(NodeInterface $node) : bool;

    /**
     * Creates and adds the element(s) to the docuement's rootElement
     *
     * @param \DomDocument $document
     * @param \DOMElement $rootElement
     * @param NodeInterface $node
     */
    abstract protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void;
}
