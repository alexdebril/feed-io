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
     * Tells if the parser can handle the feed or not
     * @param \DOMDocument $document
     * @return mixed
     */
    public function canHandle(\DOMDocument $document)
    {
        // TODO: Implement canHandle() method.
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