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

use PHPUnit\Framework\TestCase;

class RssTest extends ParserTestAbstract
{
    public const SAMPLE_FILE = 'rss/sample-rss.xml';

    public const ENCLOSURE_FILE = 'rss/rss-enclosure.xml';

    public const DC_CREATOR_FILE = 'rss/rss-with-dc-creator.xml';

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
            $this->assertIsString($media->getUrl());
        }

        $this->assertEquals(1, $count);
    }

    public function testDcCreator()
    {
        $document = $this->buildDomDocument(static::DC_CREATOR_FILE);
        $feed = $this->object->parse($document, new Feed());

        $count = 0;
        foreach ($feed as $item) {
            $count++;
            $author = $item->getAuthor();

            $this->assertInstanceOf('\FeedIo\Feed\Item\AuthorInterface', $author);
            $this->assertEquals('Author Name', $author->getName());
        }

        $this->assertEquals(1, $count);
    }
}
