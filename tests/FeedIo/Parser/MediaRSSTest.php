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
use Psr\Log\NullLogger;

use \PHPUnit\Framework\TestCase;

class MediaRssTest extends TestCase
{
    const SAMPLE_FILE = 'rss/sample-youtube.xml';

    /**
     * @return \FeedIo\StandardAbstract
     */
    public function getStandard()
    {
        return new Atom(new DateTimeBuilder());
    }

    public function setUp(): void
    {
        $standard = $this->getStandard();
        $this->object = new Parser($standard, new NullLogger());
    }

    /**
     * @param $filename
     * @return Document
     */
    protected function buildDomDocument($filename)
    {
        $file = dirname(__FILE__)."/../../samples/{$filename}";
        $domDocument = new \DOMDocument();
        $domDocument->load($file, LIBXML_NOBLANKS | LIBXML_COMPACT);

        return new Document($domDocument->saveXML());
    }

    public function testYoutubeFeed()
    {
        $document = $this->buildDomDocument(static::SAMPLE_FILE);
        $feed = $this->object->parse($document, new Feed());

        $this->assertEquals(1, count($feed));
        foreach ($feed as $item) {
            $this->assertTrue($item->hasMedia());
            $media = $item->getMedias()->current();

            $this->assertInstanceOf('\FeedIo\Feed\Item\MediaInterface', $media);
            $this->assertEquals('YT_VIDEO_TITLE', $media->getTitle());
            $this->assertEquals('https://i2.ytimg.com/vi/YT_VIDEO_ID/hqdefault.jpg', $media->getThumbnail());
            $this->assertEquals("This is a\nmultiline\ndescription", $media->getDescription());
            $this->assertEquals('https://www.youtube.com/v/YT_VIDEO_ID?version=3', $media->getUrl());
        }
    }
}
