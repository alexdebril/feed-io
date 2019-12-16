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
use FeedIo\Parser\XmlParser as Parser;
use FeedIo\Reader\Document;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Atom;
use FeedIo\Standard\Rss;
use Psr\Log\NullLogger;

use \PHPUnit\Framework\TestCase;

class MediaRssTest extends TestCase
{
    const YOUTUBE_SAMPLE_FILE = 'rss/sample-youtube.xml';

    /**
     * @param $filename
     * @return Document
     */
    protected function getMediaFromFile($filename, $nb=1)
    {
        $file = dirname(__FILE__)."/../../samples/{$filename}";
        $domDocument = new \DOMDocument();
        $domDocument->load($file, LIBXML_NOBLANKS | LIBXML_COMPACT);

        $document = new Document($domDocument->saveXML());
        $standard = new Atom(new DateTimeBuilder());
        $parser = new Parser($standard, new NullLogger());
        $feed = $parser->parse($document, new Feed());

        return $this->getMediaFromFeed($feed, $nb);
    }

    protected function getMediaFromXML($xml, $nb=1)
    {
        $document = new Document($xml);
        $standard = new Rss(new DateTimeBuilder());
        $parser = new Parser($standard, new NullLogger());
        $feed = $parser->parse($document, new Feed());
        return $this->getMediaFromFeed($feed, $nb);
    }

    protected function getMediaFromFeed($feed, $nb=1)
    {
        $this->assertEquals(1, count($feed));
        $item = $feed->current();

        $this->assertTrue($item->hasMedia());
        $this->assertEquals($nb, count($item->getMedias()));
        if ($nb > 1) {
            return $item->getMedias();
        }

        $media = $item->getMedias()->current();
        $this->assertInstanceOf('\FeedIo\Feed\Item\MediaInterface', $media);
        return $media;
    }

    public function testYoutubeFeed()
    {
        $media = $this->getMediaFromFile(static::YOUTUBE_SAMPLE_FILE);
        $this->assertEquals('YT_VIDEO_TITLE', $media->getTitle());
        $this->assertEquals('https://i2.ytimg.com/vi/YT_VIDEO_ID/hqdefault.jpg', $media->getThumbnail());
        $this->assertEquals("This is a\nmultiline\ndescription", $media->getDescription());
        $this->assertEquals('https://www.youtube.com/v/YT_VIDEO_ID?version=3', $media->getUrl());
    }
}
