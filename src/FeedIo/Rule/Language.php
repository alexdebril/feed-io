<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use FeedIo\Feed\NodeInterface;
use FeedIo\FeedInterface;
use FeedIo\RuleAbstract;

class Language extends RuleAbstract
{
    const NODE_NAME = 'language';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     * @return $this
     */
    public function setProperty(NodeInterface $node, \DOMElement $element)
    {
        if ($node instanceof FeedInterface) {
            $node->set(static::NODE_NAME, $element->nodeValue);
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
        if (!($node instanceof FeedInterface) || is_null($node->getLanguage())) {
            return;
        }

        return $document->createElement($this->getNodeName(), $node->getLanguage());
    }
}
