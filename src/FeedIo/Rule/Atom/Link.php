<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 15:50
 */

namespace FeedIo\Rule\Atom;


use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Link extends RuleAbstract
{

    const NODE_NAME = 'link';

    /**
     * @param NodeInterface $node
     * @param \DOMElement $element
     * @return mixed
     */
    public function setProperty(NodeInterface $node, \DOMElement $element)
    {
        if ( $element->hasAttribute('href') ) {
            $node->setLink($element->getAttribute('href'));
        }

        return $this;
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param \DomDocument $document
     * @param NodeInterface $node
     * @return \DomElement
     */
    public function createElement(\DomDocument $document, NodeInterface $node)
    {
        $element = $document->createElement(static::NODE_NAME);
        $element->setAttribute('href', $node->getLink());

        return $element;
    }

}
