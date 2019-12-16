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

    /**
     * From http://www.rssboard.org/media-rss#optional-elements
     *
     * Duplicated elements appearing at deeper levels of the document tree
     * have higher priority over other levels. For example, <media:content>
     * level elements are favored over <item> level elements. The priority
     * level is listed from strongest to weakest:
     * <media:content>, <media:group>, <item>, <channel>.
     */
    public function testTagsPriority()
    {
        $xml_media_content_description = '
            <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
                <channel>
                    <title>Title of page</title>
                    <link>http://www.foo.com</link>
                    <description>Description of page</description>
                    <item>
                        <title>Story about something</title>
                        <link>http://www.foo.com/item1.htm</link>
                        <media:group>
                            <media:content url="http://www.foo.com/trailer.ogg">
                                <media:description>Description from media:content</media:description>
                            </media:content>
                            <media:description>Description from media:group</media:description>
                        </media:group>
                        <media:description>Description from item</media:description>
                    </item>
                    <media:description>Description from channel</media:description>
                </channel>
            </rss>';

        $media = $this->getMediaFromXML($xml_media_content_description);
        $this->assertEquals('Description from media:content', $media->getDescription());

        $xml_media_group_description = '
            <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
                <channel>
                    <title>Title of page</title>
                    <link>http://www.foo.com</link>
                    <description>Description of page</description>
                    <item>
                        <title>Story about something</title>
                        <link>http://www.foo.com/item1.htm</link>
                        <media:group>
                            <media:content url="http://www.foo.com/trailer.ogg" />
                            <media:description>Description from media:group</media:description>
                        </media:group>
                        <media:description>Description from item</media:description>
                    </item>
                    <media:description>Description from channel</media:description>
                </channel>
            </rss>';

        $media = $this->getMediaFromXML($xml_media_group_description);
        $this->assertEquals('Description from media:group', $media->getDescription());

        $xml_item_description = '
            <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
                <channel>
                    <title>Title of page</title>
                    <link>http://www.foo.com</link>
                    <description>Description of page</description>
                    <item>
                        <title>Story about something</title>
                        <link>http://www.foo.com/item1.htm</link>
                        <media:group>
                            <media:content url="http://www.foo.com/trailer.ogg" />
                        </media:group>
                        <media:description>Description from item</media:description>
                    </item>
                    <media:description>Description from channel</media:description>
                </channel>
            </rss>';

        $media = $this->getMediaFromXML($xml_item_description);
        $this->assertEquals('Description from item', $media->getDescription());

        $xml_channel_description = '
            <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
                <channel>
                    <title>Title of page</title>
                    <link>http://www.foo.com</link>
                    <description>Description of page</description>
                    <item>
                        <title>Story about something</title>
                        <link>http://www.foo.com/item1.htm</link>
                        <media:group>
                            <media:content url="http://www.foo.com/trailer.ogg" />
                        </media:group>
                    </item>
                    <media:description>Description from channel</media:description>
                </channel>
            </rss>';

        $media = $this->getMediaFromXML($xml_channel_description);
        $this->assertEquals('Description from channel', $media->getDescription());
    }

    /**
     * media:group
     *
     * <media:group> is a sub-element of <item>. It allows grouping of <media:content> elements that are effectively the same content, yet different representations. For instance: the same song recorded in both the WAV and MP3 format. It's an optional element that must only be used for this purpose.
     *
     * TODO: We do not follow exactly the specification here as contents
     * in a <media:group> are considered as different medias, and not
     * "the same content, yet different representations". @azmeuk 2019
     */

    public function testGroupTag()
    {
        $xml_multiple_media = '
            <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
                <channel>
                    <title>Title of page</title>
                    <link>http://www.foo.com</link>
                    <description>Description of page</description>
                    <item>
                        <title>Story about something</title>
                        <link>http://www.foo.com/item1.htm</link>
                        <media:group>
                            <media:content url="http://www.foo.com/supersong.ogg">
                                <media:description>Supersong in OGG</media:description>
                            </media:content>
                            <media:content url="http://www.foo.com/supersong.flac">
                                <media:description>Supersong in FLAC</media:description>
                            </media:content>
                            <media:title>Supersong</media:title>
                        </media:group>
                        <media:content url="http://www.foo.com/hypersong.flac">
                            <media:description>Hypersong in FLAC</media:description>
                            <media:title>Hypersong</media:title>
                        </media:content>
                    </item>
                </channel>
            </rss>';

        list($supersong_ogg, $supersong_flac, $hypersong) = $this->getMediaFromXML($xml_multiple_media, 3);

        $this->assertEquals('http://www.foo.com/supersong.ogg', $supersong_ogg->getUrl());
        $this->assertEquals('http://www.foo.com/supersong.flac', $supersong_flac->getUrl());
        $this->assertEquals('http://www.foo.com/hypersong.flac', $hypersong->getUrl());

        $this->assertEquals('Supersong', $supersong_ogg->getTitle());
        $this->assertEquals('Supersong', $supersong_flac->getTitle());
        $this->assertEquals('Hypersong', $hypersong->getTitle());

        $this->assertEquals('Supersong in OGG', $supersong_ogg->getDescription());
        $this->assertEquals('Supersong in FLAC', $supersong_flac->getDescription());
        $this->assertEquals('Hypersong in FLAC', $hypersong->getDescription());
    }
}
