<?php

namespace FeedIo\Rule;

use FeedIo\Feed\NodeInterface;
use FeedIo\FeedInterface;
use FeedIo\RuleAbstract;

class Language extends RuleAbstract
{
    public const NODE_NAME = 'language';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element): void
    {
        if ($node instanceof FeedInterface) {
            $node->setLanguage($element->nodeValue);
        }
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node): bool
    {
        return $node instanceof FeedInterface && !! $node->getLanguage();
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param  \DomDocument  $document
     * @param  NodeInterface $node
     */
    public function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node): void
    {
        if (!($node instanceof FeedInterface) || is_null($node->getLanguage())) {
            return;
        }
        $rootElement->appendChild($document->createElement($this->getNodeName(), $node->getLanguage()));
    }
}
