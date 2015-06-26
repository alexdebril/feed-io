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

    const ENCLOSURE_FILE = 'rss/rss-enclosure.xml';

    /**
     * @return \FeedIo\StandardAbstract
     */
    public function getStandard()
    {
        return new Rss(new DateTimeBuilder());
    }

    public function testEnclosure()
    {
        $document = $this->buildDomDocument(static::ENCLOSURE_FILE);
        $feed = $this->object->parse($document, new Feed());

        $count = 0;
        foreach ($feed as $item) {
            $count++;
            $this->assertTrue($item->hasMedia());
            $media = $item->getMedias()->current();

            $this->assertInstanceOf('\FeedIo\Feed\Item\MediaInterface', $media);
            $this->assertEquals('audio/mpeg', $media->getType());
            $this->assertInternalType('string', $media->getUrl());
        }

        $this->assertEquals(1, $count);
    }
}
