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

use FeedIo\Feed\Item;
use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Image extends RuleAbstract
{
	// https://tools.ietf.org/html/rfc4287#section-4.2.8
    const NODE_NAME = 'logo';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element) : void
    {
		if ($node instanceof FeedInterface) {
            $node->setImage($element->nodeValue);
        }
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node) : bool
    {
		return $node instanceof FeedInterface && !! $node->getImage();
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
		if (!($node instanceof FeedInterface) || is_null($node->getImage())) {
            return;
        }
        $rootElement->appendChild($document->createElement($this->getNodeName(), $node->getImage()));
    }
}
