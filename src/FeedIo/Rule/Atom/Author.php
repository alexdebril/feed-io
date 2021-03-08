<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule\Atom;

use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Author extends RuleAbstract
{
    const NODE_NAME = 'author';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     * @return mixed
     */
    public function setProperty(NodeInterface $node, \DOMElement $element) : void
    {
        $author = $node->newAuthor();
        $author->setName($this->getChildValue($element, 'name'));
        $author->setUri($this->getChildValue($element, 'uri'));
        $author->setEmail($this->getChildValue($element, 'email'));
        $node->setAuthor($author);
    }

    /**
     * Tells if the node contains the expected value
     *
     * @param NodeInterface $node
     * @return bool
     */
    protected function hasValue(NodeInterface $node) : bool
    {
        return !! $node->getAuthor();
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        $element = $document->createElement(static::NODE_NAME);
        $this->appendNonEmptyChild($document, $element, 'name', $node->getAuthor()->getName());
        $this->appendNonEmptyChild($document, $element, 'uri', $node->getAuthor()->getUri());
        $this->appendNonEmptyChild($document, $element, 'email', $node->getAuthor()->getEmail());

        $rootElement->appendChild($element);
    }
}
