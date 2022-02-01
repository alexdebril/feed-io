<?php

declare(strict_types=1);

namespace FeedIo\Rule\Atom;

use DomDocument;
use DOMElement;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Author extends RuleAbstract
{
    public const NODE_NAME = 'author';

    public function setProperty(NodeInterface $node, DOMElement $element): void
    {
        $author = $node->newAuthor();
        $author->setName($this->getChildValue($element, 'name'));
        $author->setUri($this->getChildValue($element, 'uri'));
        $author->setEmail($this->getChildValue($element, 'email'));
        $node->setAuthor($author);
    }

    protected function hasValue(NodeInterface $node): bool
    {
        return !! $node->getAuthor();
    }

    protected function addElement(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        $element = $document->createElement(static::NODE_NAME);
        $this->appendNonEmptyChild($document, $element, 'name', $node->getAuthor()->getName());
        $this->appendNonEmptyChild($document, $element, 'uri', $node->getAuthor()->getUri());
        $this->appendNonEmptyChild($document, $element, 'email', $node->getAuthor()->getEmail());

        $rootElement->appendChild($element);
    }
}
