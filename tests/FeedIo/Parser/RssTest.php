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
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Rss;

class RssTest extends ParserTestAbstract
{

    const SAMPLE_FILE = 'rss/sample-rss.xml';

    /**
     * @return \FeedIo\StandardAbstract
     */
    public function getStandard()
    {
        return new Rss(new DateTimeBuilder());
    }
}
