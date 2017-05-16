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
          $author->setName($this->getChildValue($element, 'name'));
          $author->setUri($this->getChildValue($element, 'uri'));
          $author->setEmail($this->getChildValue($element, 'email'));
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
            $element->appendChild($document->createElement('name', $node->getAuthor()->getName()));
            $element->appendChild($document->createElement('uri', $node->getAuthor()->getUri()));
            $element->appendChild($document->createElement('email', $node->getAuthor()->getEmail()));

            return $element;
        }

        return;
    }

}
