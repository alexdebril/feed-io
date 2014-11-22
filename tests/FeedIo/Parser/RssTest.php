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


use FeedIo\Feed;
use Psr\Log\NullLogger;

class RssTest extends ParserTestAbstract
{

    const SAMPLE_FILE = 'rss/sample-rss.xml';

    /**
     * @return \FeedIo\ParserAbstract
     */
    public function getObject()
    {
        return new Rss(
            new DateTimeBuilder(),
            new NullLogger()
        );
    }
}
