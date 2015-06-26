<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 11:24
 */
namespace FeedIo\Rule;

use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class PublicId extends RuleAbstract
{
    const NODE_NAME = 'guid';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     * @return mixed
     */
    public function setProperty(NodeInterface $node, \DOMElement $element)
    {
        $node->setPublicId($element->nodeValue);

        return $node;
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
        return $document->createElement($this->getNodeName(), $node->getPublicId());
    }
}
