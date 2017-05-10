<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule\Atom;

use FeedIo\Feed\ItemInterface;
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
    public function setProperty(NodeInterface $node, \DOMElement $element)
    {
        if ($node instanceof ItemInterface) {
          $author = $node->newAuthor();
          $author->setName($this->getAttributeValue($element, 'name'));
          $author->setUri($this->getAttributeValue($element, 'uri'));
          $author->setEmail($this->getAttributeValue($element, 'email'));
          $node->setAuthor($author);
        }

        return $this;
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param  \DomDocument  $document
     * @param  NodeInterface $node
     * @return \DomElement
     */
    public function createElement(\DomDocument $document, NodeInterface $node)
    {
        if ($node instanceof ItemInterface  && !is_null($node->getAuthor())) {
            $element = $document->createElement(static::NODE_NAME);
            $element->setAttribute('name', $node->getAuthor()->getName());
            $element->setAttribute('uri', $node->getAuthor()->getUri());
            $element->setAttribute('email', $node->getAuthor()->getEmail());

            return $element;
        }

        return;
    }
}
