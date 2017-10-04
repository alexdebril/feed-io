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
use FeedIo\Rule\Link as BaseLink;

class Link extends BaseLink
{
    const NODE_NAME = 'link';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element) : void
    {
        if ($element->hasAttribute('href')) {
            $node->setLink($element->getAttribute('href'));
        }
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        $element = $document->createElement(static::NODE_NAME);
        $element->setAttribute('href', $node->getLink());

        $rootElement->appendChild($element);
    }
}
