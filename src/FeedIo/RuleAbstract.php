<?php declare(strict_types=1);
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
    public function __construct(string $nodeName = null)
    {
        $this->nodeName = is_null($nodeName) ? static::NODE_NAME : $nodeName;
    }

    /**
     * @return string
     */
    public function getNodeName() : string
    {
        return $this->nodeName;
    }

    /**
     * @param  \DOMElement $element
     * @param  string      $name
     * @return string|null
     */
    public function getAttributeValue(\DOMElement $element, $name) : ? string
    {
        if ($element->hasAttribute($name)) {
            return $element->getAttribute($name);
        }

        return null;
    }

    /**
     * @param  \DOMElement $element
     * @param  string      $name
     * @return string|null
     */
    public function getChildValue(\DOMElement $element, string $name) : ? string
    {
        $list = $element->getElementsByTagName($name);
        if ($list->length > 0) {
            return $list->item(0)->nodeValue;
        }

        return null;
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
     * Sets the attribute only if the value is not empty
     * @param DomElement $element
     * @param string     $name
     * @param string     $value
     */
    protected function setNonEmptyAttribute(\DomElement $element, string $name, string $value = null) : void
    {
        if (! is_null($value)) {
            $element->setAttribute($name, $value);
        }
    }

    /**
     * Appends a child node only if the value is not null
     * @param DomDocument $document
     * @param DOMElement  $element
     * @param string      $name
     * @param string      $value
     */
    protected function appendNonEmptyChild(\DomDocument $document, \DOMElement $element, string $name, string $value = null) : void
    {
        if (! is_null($value)) {
            $element->appendChild($document->createElement($name, $value));
        }
    }

    /**
     * Sets the accurate $item property according to the DomElement content
     *
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    abstract public function setProperty(NodeInterface $node, \DOMElement $element) : void;

    /**
     * Tells if the node contains the expected value
     *
     * @param NodeInterface $node
     * @return bool
     */
    abstract protected function hasValue(NodeInterface $node) : bool;

    /**
     * Creates and adds the element(s) to the document's rootElement
     *
     * @param \DomDocument $document
     * @param \DOMElement $rootElement
     * @param NodeInterface $node
     */
    abstract protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void;
}
