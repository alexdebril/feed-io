<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Title extends RuleAbstract
{
    public const NODE_NAME = 'title';

    /**
     * Sets the accurate $item property according to the DomElement content
     *
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element): void
    {
        $node->setTitle($element->nodeValue);
    }

    /**
     * Tells if the node contains the expected value
     *
     * @param NodeInterface $node
     * @return bool
     */
    protected function hasValue(NodeInterface $node): bool
    {
        return !! $node->getTitle();
    }

    /**
     * Creates and adds the element(s) to the docuement's rootElement
     *
     * @param \DomDocument $document
     * @param \DOMElement $rootElement
     * @param NodeInterface $node
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node): void
    {
        $title = htmlspecialchars($node->getTitle());
        $element = $document->createElement(static::NODE_NAME, $title);
        $rootElement->appendChild($element);
    }
}
