<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser;


use FeedIo\FeedInterface;
use FeedIo\ParserAbstract;

class Rss extends ParserAbstract
{
    /**
     * RSS document must have a <rss> root node
     */
    const ROOT_NODE_TAGNAME = 'rss';

    /**
     * Tells if the parser can handle the feed or not
     * @param \DOMDocument $document
     * @return mixed
     */
    public function canHandle(\DOMDocument $document)
    {
        return self::ROOT_NODE_TAGNAME === $document->documentElement->tagName;
    }

    /**
     * Performs the actual conversion into a FeedContent instance
     * @param \DOMDocument $document
     * @param FeedInterface $feed
     * @return FeedInterface
     */
    protected function parseBody(\DOMDocument $document, FeedInterface $feed)
    {
        // TODO: Implement parseBody() method.
    }

} 