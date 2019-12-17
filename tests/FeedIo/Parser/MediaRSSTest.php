<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * The Media-RSS specification is available here:
 * http://www.rssboard.org/media-rss
 */

namespace FeedIo\Parser;

use FeedIo\Feed;
use FeedIo\Feed\Item\MediaConstants;
use FeedIo\Feed\Item\MediaContentMedium;
use FeedIo\Feed\Item\MediaContentExpression;
use FeedIo\Feed\Item\MediaDescriptionType;
use FeedIo\Feed\Item\MediaTitleType;
use FeedIo\Feed\Item\MediaHashAlgo;
use FeedIo\Feed\Item\MediaCreditScheme;
use FeedIo\Feed\Item\MediaPriceType;
use FeedIo\Feed\Item\MediaRestrictionRelationship;
use FeedIo\Feed\Item\MediaRestrictionType;
use FeedIo\Feed\Item\MediaRightsStatus;
use FeedIo\Feed\Item\MediaStatusValue;
use FeedIo\Feed\Item\MediaTextType;
use FeedIo\Feed\Item\MediaThumbnail;
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

    protected function getMediaFromXMLSample($sample)
    {
        return $this->getMediaFromXML('
            <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
                <channel>
                    <title>Title of page</title>
                    <link>http://www.foo.com</link>
                    <description>Description of page</description>
                    <item>
                        <title>Story about something</title>
                        <link>http://www.foo.com/item1.htm</link>
                        <media:content url="http://www.foo.com/trailer.ogg">'.
                            $sample.
                        '</media:content>
                    </item>
                </channel>
            </rss>');
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
        $this->assertEquals('https://i2.ytimg.com/vi/YT_VIDEO_ID/hqdefault.jpg', $media->getThumbnail()->getUrl());
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

    /**
     * media:content
     *
     * <media:content> is a sub-element of either <item> or <media:group>. Media objects that are not the same content should not be included in the same <media:group> element. The sequence of these items implies the order of presentation. While many of the attributes appear to be audio/video specific, this element can be used to publish any type of media. It contains 14 attributes, most of which are optional.
     *
     * <media:content
     *   url="http://www.foo.com/movie.mov"
     *   fileSize="12216320"
     *   type="video/quicktime"
     *   medium="video"
     *   isDefault="true"
     *   expression="full"
     *   bitrate="128"
     *   framerate="25"
     *   samplingrate="44.1"
     *   channels="2"
     *   duration="185"
     *   height="200"
     *   width="300"
     *   lang="en" />
     *
     * url should specify the direct URL to the media object. If not included, a <media:player> element must be specified.
     *
     * fileSize is the number of bytes of the media object. It is an optional attribute.
     *
     * type is the standard MIME type of the object. It is an optional attribute.
     *
     * medium is the type of object (image | audio | video | document | executable). While this attribute can at times seem redundant if type is supplied, it is included because it simplifies decision making on the reader side, as well as flushes out any ambiguities between MIME type and object type. It is an optional attribute.
     *
     * isDefault determines if this is the default object that should be used for the <media:group>. There should only be one default object per <media:group>. It is an optional attribute.
     *
     * expression determines if the object is a sample or the full version of the object, or even if it is a continuous stream (sample | full | nonstop). Default value is "full". It is an optional attribute.
     *
     * bitrate is the kilobits per second rate of media. It is an optional attribute.
     *
     * framerate is the number of frames per second for the media object. It is an optional attribute.
     *
     * samplingrate is the number of samples per second taken to create the media object. It is expressed in thousands of samples per second (kHz). It is an optional attribute.
     *
     * channels is number of audio channels in the media object. It is an optional attribute.
     *
     * duration is the number of seconds the media object plays. It is an optional attribute.
     *
     * height is the height of the media object. It is an optional attribute.
     *
     * width is the width of the media object. It is an optional attribute.
     *
     * lang is the primary language encapsulated in the media object. Language codes possible are detailed in RFC 3066. This attribute is used similar to the xml:lang attribute detailed in the XML 1.0 Specification (Third Edition). It is an optional attribute.
     *
     * These optional attributes, along with the optional elements below, contain the primary metadata entries needed to index and organize media content. Additional supported attributes for describing images, audio and video may be added in future revisions of this document.
     *
     * Note: While both <media:content> and <media:group> have no limitations on the number of times they can appear, the general nature of RSS should be preserved: an <item> represents a "story." Simply stated, this is similar to the blog style of syndication. However, if one is using this module to strictly publish media, there should be one <item> element for each media object/group. This is to allow for proper attribution for the origination of the media content through the <link> element. It also allows the full benefit of the other RSS elements to be realized.
     */

    public function testContentTag()
    {
        $xml = '
            <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
                <channel>
                    <title>Title of page</title>
                    <link>http://www.foo.com</link>
                    <description>Description of page</description>
                    <item>
                        <title>Story about something</title>
                        <link>http://www.foo.com/item1.htm</link>
                        <media:content
                            url="http://www.foo.com/movie.mov"
                            fileSize="2000"
                            type="video/quicktime"
                            medium="video"
                            isDefault="true"
                            expression="full"
                            bitrate="128"
                            framerate="25"
                            samplingrate="44.1"
                            channels="2"
                            duration="185"
                            height="200"
                            width="300"
                            lang="en"/>
                    </item>
                </channel>
            </rss>';

        $media = $this->getMediaFromXML($xml);

        $this->assertEquals('http://www.foo.com/movie.mov', $media->getUrl());
        $this->assertEquals(2000, $media->getContent()->getFileSize());
        $this->assertEquals(128, $media->getContent()->getBitrate());
        $this->assertEquals(25, $media->getContent()->getFramerate());
        $this->assertEquals(44.1, $media->getContent()->getSamplingrate());
        $this->assertEquals(185, $media->getContent()->getDuration());
        $this->assertEquals(200, $media->getContent()->getHeight());
        $this->assertEquals(300, $media->getContent()->getWidth());
        $this->assertEquals('en', $media->getContent()->getLang());
        $this->assertEquals('video/quicktime', $media->getType());
        $this->assertEquals(MediaContentExpression::Full, $media->getContent()->getExpression());
        $this->assertEquals(MediaContentMedium::Video, $media->getContent()->getMedium());
        $this->assertTrue($media->getContent()->isDefault());
    }

    public function testContentTagDefaults()
    {
        $xml = '
            <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/">
                <channel>
                    <title>Title of page</title>
                    <link>http://www.foo.com</link>
                    <description>Description of page</description>
                    <item>
                        <title>Story about something</title>
                        <link>http://www.foo.com/item1.htm</link>
                        <media:content url="http://www.foo.com/movie.mov" />
                    </item>
                </channel>
            </rss>';

        $media = $this->getMediaFromXML($xml);

        $this->assertEquals('http://www.foo.com/movie.mov', $media->getUrl());
        $this->assertEquals(null, $media->getContent()->getFileSize());
        $this->assertEquals(null, $media->getContent()->getBitrate());
        $this->assertEquals(null, $media->getContent()->getFramerate());
        $this->assertEquals(null, $media->getContent()->getSamplingrate());
        $this->assertEquals(null, $media->getContent()->getDuration());
        $this->assertEquals(null, $media->getContent()->getHeight());
        $this->assertEquals(null, $media->getContent()->getWidth());
        $this->assertEquals(null, $media->getContent()->getLang());
        $this->assertEquals(null, $media->getType());
        $this->assertEquals(null, $media->getContent()->getExpression());
        $this->assertEquals(null, $media->getContent()->getMedium());
        $this->assertTrue($media->getContent()->isDefault());
    }

    /**
     * media:rating
     *
     * This allows the permissible audience to be declared. If this element is not included, it assumes that no restrictions are necessary. It has one optional attribute.
     *
     * <media:rating scheme="urn:simple">adult</media:rating>
     * <media:rating scheme="urn:icra">r (cz 1 lz 1 nz 1 oz 1 vz 1)</media:rating>
     * <media:rating scheme="urn:mpaa">pg</media:rating>
     * <media:rating scheme="urn:v-chip">tv-y7-fv</media:rating>
     *
     * scheme is the URI that identifies the rating scheme. It is an optional attribute. If this attribute is not included, the default scheme is urn:simple (adult | nonadult).
     */

    public function testRatingTag()
    {
        $xml = '<media:rating scheme="urn:icra">r (cz 1 lz 1 nz 1 oz 1 vz 1)</media:rating>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("r (cz 1 lz 1 nz 1 oz 1 vz 1)", $media->getRating()->getContent());
        $this->assertEquals('urn:icra', $media->getRating()->getScheme());
    }

    public function testRatingTagDefaults()
    {
        $xml = '<media:rating>adult</media:rating>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("adult", $media->getRating()->getContent());
        $this->assertEquals('urn:simple', $media->getRating()->getScheme());
    }

    /**
     * media:title
     *
     * The title of the particular media object. It has one optional attribute.
     *
     * <media:title type="plain">The Judy's -- The Moo Song</media:title>
     *
     * type specifies the type of text embedded. Possible values are either "plain" or "html". Default value is "plain". All HTML must be entity-encoded. It is an optional attribute.
     */

    public function testTitleTag()
    {
        $xml = '<media:title type="plain">The Judy\'s -- The Moo Song</media:title>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("The Judy's -- The Moo Song", $media->getTitle());
        $this->assertEquals(MediaTitleType::Plain, $media->getTitleType());
    }

    public function testTitleTagDefaults()
    {
        $xml = '<media:title>The Judy\'s -- The Moo Song</media:title>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("The Judy's -- The Moo Song", $media->getTitle());
        $this->assertEquals(MediaTitleType::Plain, $media->getTitleType());
    }

    /**
     * media:description
     *
     * Short description describing the media object typically a sentence in length. It has one optional attribute.
     *
     * <media:description type="plain">This was some really bizarre band I listened to as a young lad.</media:description>
     *
     * type specifies the type of text embedded. Possible values are either "plain" or "html". Default value is "plain". All HTML must be entity-encoded. It is an optional attribute.
     */

    public function testDescriptionTag()
    {
        $xml = '<media:description type="plain">This was some really bizarre band I listened to as a young lad.</media:description>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("This was some really bizarre band I listened to as a young lad.", $media->getDescription());
        $this->assertEquals(MediaDescriptionType::Plain, $media->getDescriptionType());
    }

    public function testDescriptionTagDefaults()
    {
        $xml = '<media:description>This was some really bizarre band I listened to as a young lad.</media:description>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("This was some really bizarre band I listened to as a young lad.", $media->getDescription());
        $this->assertEquals(MediaDescriptionType::Plain, $media->getDescriptionType());
    }

    /**
     * media:keywords
     *
     * Highly relevant keywords describing the media object with typically a maximum of 10 words. The keywords and phrases should be comma-delimited.
     *
     * <media:keywords>kitty, cat, big dog, yarn, fluffy</media:keywords>
     */

    public function testKeywordsTag()
    {
        $xml = '<media:keywords>kitty, cat, big dog, yarn, fluffy</media:keywords>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(["kitty", "cat", "big dog", "yarn", "fluffy"], $media->getKeywords());
    }

    /**
     * media:thumbnail
     *
     * Allows particular images to be used as representative images for the media object. If multiple thumbnails are included, and time coding is not at play, it is assumed that the images are in order of importance. It has one required attribute and three optional attributes.
     *
     * <media:thumbnail url="http://www.foo.com/keyframe.jpg" width="75" height="50" time="12:05:01.123" />
     *
     * url specifies the url of the thumbnail. It is a required attribute.
     *
     * height specifies the height of the thumbnail. It is an optional attribute.
     *
     * width specifies the width of the thumbnail. It is an optional attribute.
     *
     * time specifies the time offset in relation to the media object. Typically this is used when creating multiple keyframes within a single video. The format for this attribute should be in the DSM-CC's Normal Play Time (NTP) as used in RTSP [RFC 2326 3.6 Normal Play Time]. It is an optional attribute.
     *
     * Notes:
     *
     * NTP has a second or subsecond resolution. It is specified as H:M:S.h (npt-hhmmss) or S.h (npt-sec), where H=hours, M=minutes, S=second and h=fractions of a second.
     *
     * A possible alternative to NTP would be SMPTE. It is believed that NTP is simpler and easier to use.
     */

    public function testThumbnailsTag()
    {
        $xml = '<media:thumbnail url="http://www.foo.com/keyframe.jpg" width="75" height="50" time="12:05:01.123" />';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("http://www.foo.com/keyframe.jpg", $media->getThumbnail()->getUrl());
        $this->assertEquals(75, $media->getThumbnail()->getWidth());
        $this->assertEquals(50, $media->getThumbnail()->getHeight());
        $this->assertEquals(new \DateTime("12:05:01.123"), $media->getThumbnail()->getTime());
    }

    public function testThumbnailsTagDefaults()
    {
        $xml = '<media:thumbnail url="http://www.foo.com/keyframe.jpg" />';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("http://www.foo.com/keyframe.jpg", $media->getThumbnail()->getUrl());
        $this->assertEquals(null, $media->getThumbnail()->getWidth());
        $this->assertEquals(null, $media->getThumbnail()->getHeight());
        $this->assertEquals(null, $media->getThumbnail()->getTime());
    }

    /**
     * media:category
     *
     * Allows a taxonomy to be set that gives an indication of the type of media content, and its particular contents. It has two optional attributes.
     *
     * <media:category scheme="http://search.yahoo.com/mrss/category_schema">music/artist/album/song</media:category>
     *
     * <media:category scheme="http://dmoz.org" label="Ace Ventura - Pet Detective">Arts/Movies/Titles/A/Ace_Ventura_Series/Ace_Ventura_ -_Pet_Detective</media:category>
     *
     * <media:category scheme="urn:flickr:tags">ycantpark mobile</media:category>
     *
     * scheme is the URI that identifies the categorization scheme. It is an optional attribute. If this attribute is not included, the default scheme is "http://search.yahoo.com/mrss/category_schema".
     *
     * label is the human readable label that can be displayed in end user applications. It is an optional attribute.
     */

    public function testCategoryTag()
    {
        $xml = '<media:category scheme="http://dmoz.org" label="Ace Ventura - Pet Detective">Arts/Movies/Titles/A/Ace_Ventura_Series/Ace_Ventura_ -_Pet_Detective</media:category>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("Arts/Movies/Titles/A/Ace_Ventura_Series/Ace_Ventura_ -_Pet_Detective", $media->getCategory()->getText());
        $this->assertEquals("Ace Ventura - Pet Detective", $media->getCategory()->getLabel());
        $this->assertEquals("http://dmoz.org", $media->getCategory()->getScheme());
    }

    public function testCategoryTagDefaults()
    {
        $xml = '<media:category>foobar</media:category>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("foobar", $media->getCategory()->getText());
        $this->assertEquals(null, $media->getCategory()->getLabel());
        $this->assertEquals("http://search.yahoo.com/mrss/category_schema", $media->getCategory()->getScheme());
    }

    /**
     * media:hash
     *
     * This is the hash of the binary media file. It can appear multiple times as long as each instance is a different algo. It has one optional attribute.
     *
     * <media:hash algo="md5">dfdec888b72151965a34b4b59031290a</media:hash>
     *
     * algo indicates the algorithm used to create the hash. Possible values are "md5" and "sha-1". Default value is "md5". It is an optional attribute.
     */

    public function testHashTag()
    {
        $xml = '<media:hash algo="SHA1">dfdec888b72151965a34b4b59031290a</media:hash>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("dfdec888b72151965a34b4b59031290a", $media->getHash()->getContent());
        $this->assertEquals(MediaHashAlgo::SHA1, $media->getHash()->getAlgo());
    }

    public function testHashTagDefaults()
    {
        $xml = '<media:hash>dfdec888b72151965a34b4b59031290a</media:hash>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("dfdec888b72151965a34b4b59031290a", $media->getHash()->getContent());
        $this->assertEquals(MediaHashAlgo::MD5, $media->getHash()->getAlgo());
    }

    /**
     * media:player
     *
     * Allows the media object to be accessed through a web browser media player console. This element is required only if a direct media url attribute is not specified in the <media:content> element. It has one required attribute and two optional attributes.
     *
     * <media:player url="http://www.foo.com/player?id=1111" height="200" width="400" />
     *
     * url is the URL of the player console that plays the media. It is a required attribute.
     *
     * height is the height of the browser window that the URL should be opened in. It is an optional attribute.
     *
     * width is the width of the browser window that the URL should be opened in. It is an optional attribute.
     */

    public function testPlayerTag()
    {
        $xml = '<media:player url="http://www.foo.com/player?id=1111" height="200" width="400" />';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals('http://www.foo.com/player?id=1111', $media->getPlayer()->getUrl());
        $this->assertEquals(400, $media->getPlayer()->getWidth());
        $this->assertEquals(200, $media->getPlayer()->getHeight());
    }

    public function testPlayerTagDefaults()
    {
        $xml = '<media:player url="http://www.foo.com/player?id=1111" />';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals('http://www.foo.com/player?id=1111', $media->getPlayer()->getUrl());
        $this->assertEquals(null, $media->getPlayer()->getWidth());
        $this->assertEquals(null, $media->getPlayer()->getHeight());
    }

    /**
     * media:credit
     *
     * Notable entity and the contribution to the creation of the media object. Current entities can include people, companies, locations, etc. Specific entities can have multiple roles, and several entities can have the same role. These should appear as distinct <media:credit> elements. It has two optional attributes.
     *
     * <media:credit role="producer" scheme="urn:ebu">entity name</media:credit>
     *
     * <media:credit role="owner" scheme="urn:yvs">copyright holder of the entity</media:credit>
     *
     * role specifies the role the entity played. Must be lowercase. It is an optional attribute.
     *
     * scheme is the URI that identifies the role scheme. It is an optional attribute and possible values for this attribute are ( urn:ebu | urn:yvs ) . The default scheme is "urn:ebu". The list of roles supported under urn:ebu scheme can be found at European Broadcasting Union Role Codes. The roles supported under urn:yvs scheme are ( uploader | owner ).
     *
     * Example roles:
     *
     *     actor
     *     anchor person
     *     author
     *     choreographer
     *     composer
     *     conductor
     *     director
     *     editor
     *     graphic designer
     *     grip
     *     illustrator
     *     lyricist
     *     music arranger
     *     music group
     *     musician
     *     orchestra
     *     performer
     *     photographer
     *     producer
     *     reporter
     *     vocalist
     *
     * Additional roles: European Broadcasting Union Role Codes.
     */

    public function testCreditTag()
    {
        $xml = '
            <media:credit role="producer" scheme="urn:ebu">entity name</media:credit>
            <media:credit role="owner" scheme="urn:yvs">copyright holder of the entity</media:credit>';
        $media = $this->getMediaFromXMLSample($xml);

        list($credit1, $credit2) = $media->getCredits();

        $this->assertEquals(MediaCreditScheme::URN_EBU, $credit1->getScheme());
        $this->assertEquals("producer", $credit1->getRole());
        $this->assertEquals("entity name", $credit1->getValue());

        $this->assertEquals(MediaCreditScheme::URN_YVS, $credit2->getScheme());
        $this->assertEquals("owner", $credit2->getRole());
        $this->assertEquals("copyright holder of the entity", $credit2->getValue());
    }

    public function testCreditTagDefaults()
    {
        $xml = '<media:credit>entity name</media:credit>';
        $media = $this->getMediaFromXMLSample($xml);

        list($credit) = $media->getCredits();

        $this->assertEquals(MediaCreditScheme::URN_EBU, $credit->getScheme());
        $this->assertEquals(null, $credit->getRole());
        $this->assertEquals("entity name", $credit->getValue());
    }

    /**
     * media:copyright
     *
     * Copyright information for the media object. It has one optional attribute.
     *
     * <media:copyright url="http://blah.com/additional-info.html">2005 FooBar Media</media:copyright>
     *
     * url is the URL for a terms of use page or additional copyright information. If the media is operating under a Creative Commons license, the Creative Commons module should be used instead. It is an optional attribute.
     */

    public function testCopyrightTag()
    {
        $xml = '<media:copyright url="http://blah.com/additional-info.html">2005 FooBar Media</media:copyright>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals('2005 FooBar Media', $media->getCopyright()->getContent());
        $this->assertEquals('http://blah.com/additional-info.html', $media->getCopyright()->getUrl());
    }

    public function testCopyrightTagDefaults()
    {
        $xml = '<media:copyright>2005 FooBar Media</media:copyright>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals('2005 FooBar Media', $media->getCopyright()->getContent());
        $this->assertEquals(null, $media->getCopyright()->getUrl());
    }


    /**
     * media:text
     *
     * Allows the inclusion of a text transcript, closed captioning or lyrics of the media content. Many of these elements are permitted to provide a time series of text. In such cases, it is encouraged, but not required, that the elements be grouped by language and appear in time sequence order based on the start time. Elements can have overlapping start and end times. It has four optional attributes.
     *
     * <media:text type="plain" lang="en" start="00:00:03.000" end="00:00:10.000"> Oh, say, can you see</media:text>
     *
     * <media:text type="plain" lang="en" start="00:00:10.000" end="00:00:17.000">By the dawn's early light</media:text>
     *
     * type specifies the type of text embedded. Possible values are either "plain" or "html". Default value is "plain". All HTML must be entity-encoded. It is an optional attribute.
     *
     * lang is the primary language encapsulated in the media object. Language codes possible are detailed in RFC 3066. This attribute is used similar to the xml:lang attribute detailed in the XML 1.0 Specification (Third Edition). It is an optional attribute.
     *
     * start specifies the start time offset that the text starts being relevant to the media object. An example of this would be for closed captioning. It uses the NTP time code format (see: the time attribute used in <media:thumbnail>). It is an optional attribute.
     *
     * end specifies the end time that the text is relevant. If this attribute is not provided, and a start time is used, it is expected that the end time is either the end of the clip or the start of the next <media:text> element.
     */

    public function testTextTag()
    {
        $xml = '
            <media:text type="plain" lang="en" start="00:00:03.000" end="00:00:10.000">Oh, say, can you see</media:text>
            <media:text type="html" lang="en" start="00:00:10.000" end="00:00:17.000">By the dawn\'s early light</media:text>';
        $media = $this->getMediaFromXMLSample($xml);

        list($text1, $text2) = $media->getTexts();
        $this->assertEquals('Oh, say, can you see', $text1->getValue());
        $this->assertEquals(MediaTextType::Plain, $text1->getType());
        $this->assertEquals("en", $text1->getLang());
        $this->assertEquals(new \DateTime("00:00:03.000"), $text1->getStart());
        $this->assertEquals(new \DateTime("00:00:10.000"), $text1->getEnd());

        $this->assertEquals(MediaTextType::HTML, $text2->getType());
    }

    public function testTextTagDefaults()
    {
        $xml = '<media:text>Oh, say, can you see</media:text>';
        $media = $this->getMediaFromXMLSample($xml);

        list($text1) = $media->getTexts();
        $this->assertEquals('Oh, say, can you see', $text1->getValue());
        $this->assertEquals(MediaTextType::Plain, $text1->getType());
        $this->assertEquals(null, $text1->getLang());
        $this->assertEquals(null, $text1->getStart());
        $this->assertEquals(null, $text1->getEnd());
    }

    /**
     * media:restriction
     *
     * Allows restrictions to be placed on the aggregator rendering the media in the feed. Currently, restrictions are based on distributor (URI), country codes and sharing of a media object. This element is purely informational and no obligation can be assumed or implied. Only one <media:restriction> element of the same type can be applied to a media object -- all others will be ignored. Entities in this element should be space-separated. To allow the producer to explicitly declare his/her intentions, two literals are reserved: "all", "none". These literals can only be used once. This element has one required attribute and one optional attribute (with strict requirements for its exclusion).
     *
     * <media:restriction relationship="allow" type="country">au us</media:restriction>
     *
     * relationship indicates the type of relationship that the restriction represents (allow | deny). In the example above, the media object should only be syndicated in Australia and the United States. It is a required attribute.
     *
     * Note: If the "allow" element is empty and the type of relationship is "allow", it is assumed that the empty list means "allow nobody" and the media should not be syndicated.
     *
     * A more explicit method would be:
     *
     * <media:restriction relationship="allow" type="country">au us</media:restriction>
     *
     * type specifies the type of restriction (country | uri | sharing ) that the media can be syndicated. It is an optional attribute; however can only be excluded when using one of the literal values "all" or "none".
     *
     * "country" allows restrictions to be placed based on country code. [ISO 3166]
     *
     * "uri" allows restrictions based on URI. Examples: urn:apple, http://images.google.com, urn:yahoo, etc.
     *
     * "sharing" allows restriction on sharing.
     *
     * "deny" means content cannot be shared -- for example via embed tags. If the sharing type is not present, the default functionality is to allow sharing. For example:
     *
     * <media:restriction type="sharing" relationship="deny" />
     */

    public function testRestrictionTag()
    {
        $xml = '<media:restriction relationship="allow" type="country">au us</media:restriction>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("au us", $media->getRestriction()->getContent());
        $this->assertEquals(MediaRestrictionRelationship::Allow, $media->getRestriction()->getRelationship());
        $this->assertEquals(MediaRestrictionType::Country, $media->getRestriction()->getType());
    }

    public function testRestrictionTagDefaults()
    {
        $xml = '<media:restriction relationship="allow">all</media:restriction>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("all", $media->getRestriction()->getContent());
        $this->assertEquals(MediaRestrictionRelationship::Allow, $media->getRestriction()->getRelationship());
        $this->assertEquals(MediaRestrictionType::Sharing, $media->getRestriction()->getType());
    }

    /**
     * media:community
     *
     * This element stands for the community related content. This allows inclusion of the user perception about a media object in the form of view count, ratings and tags.
     *
     * <media:community>
     *   <media:starRating average="3.5" count="20" min="1" max="10" />
     *   <media:statistics views="5" favorites="5" />
     *   <media:tags>news: 5, abc:3, reuters</media:tags>
     * </media:community>
     *
     * starRating This element specifies the rating-related information about a media object. Valid attributes are average, count, min and max.
     *
     * statistics This element specifies various statistics about a media object like the view count and the favorite count. Valid attributes are views and favorites.
     *
     * tags This element contains user-generated tags separated by commas in the decreasing order of each tag's weight. Each tag can be assigned an integer weight in tag_name:weight format. It's up to the provider to choose the way weight is determined for a tag; for example, number of occurences can be one way to decide weight of a particular tag. Default weight is 1.
     */

    public function testCommunityTag()
    {
        $xml = '
            <media:community>
                <media:starRating average="3.5" count="20" min="1" max="10" />
                <media:statistics views="5" favorites="5" />
                <media:tags>news: 5, abc:3</media:tags>
            </media:community>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(3.5, $media->getCommunity()->getStarRatingAverage());
        $this->assertEquals(20, $media->getCommunity()->getStarRatingCount());
        $this->assertEquals(1, $media->getCommunity()->getStarRatingMin());
        $this->assertEquals(10, $media->getCommunity()->getStarRatingMax());
        $this->assertEquals(5, $media->getCommunity()->getNbViews());
        $this->assertEquals(5, $media->getCommunity()->getNbFavorites());
        $this->assertEquals(array('news' => 5, 'abc' => 3), $media->getCommunity()->getTags());
    }

    public function testCommunityTagDefaults()
    {
        $xml = '<media:community />';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(null, $media->getCommunity()->getStarRatingAverage());
        $this->assertEquals(null, $media->getCommunity()->getStarRatingCount());
        $this->assertEquals(null, $media->getCommunity()->getStarRatingMin());
        $this->assertEquals(null, $media->getCommunity()->getStarRatingMax());
        $this->assertEquals(null, $media->getCommunity()->getNbViews());
        $this->assertEquals(null, $media->getCommunity()->getNbFavorites());
        $this->assertEquals(array(), $media->getCommunity()->getTags());
    }

    /**
     * media:comments
     *
     * Allows inclusion of all the comments a media object has received.
     *
     * <media:comments>
     *   <media:comment>comment1</media:comment>
     *   <media:comment>comment2</media:comment>
     *   <media:comment>comment3</media:comment>
     * </media:comments>
     */

    public function testCommentsTag()
    {
        $xml = '
            <media:comments>
                <media:comment>comment1</media:comment>
                <media:comment>comment2</media:comment>
            </media:comments>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(array("comment1", "comment2"), $media->getComments());
    }

    public function testCommentsTagDefaults()
    {
        $xml = '<media:comments />';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(array(), $media->getComments());
    }

    /**
     * media:embed
     *
     * Sometimes player-specific embed code is needed for a player to play any video. <media:embed> allows inclusion of such information in the form of key-value pairs.
     *
     * <media:embed url="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.2" width="512" height="323">
     *   <media:param name="type">application/x-shockwave-flash</media:param>
     *   <media:param name="width">512</media:param>
     *   <media:param name="height">323</media:param>
     *   <media:param name="allowFullScreen">true</media:param>
     *   <media:param name="flashVars">
     *     id=7809705&amp;vid=2666306&amp;lang=en-us&amp;intl=us&amp;thumbUrl=http%3A//us.i1.yimg.com/us.yimg.com/i/us/sch/cn/video06/2666306_rndf1e4205b_19.jpg
     *   </media:param>
     * </media:embed>
     */

    public function testEmbedTag()
    {
        $xml = '
            <media:embed url="http://www.foo.com/player.swf" width="512" height="323">
                <media:param name="type">application/x-shockwave-flash</media:param>
                <media:param name="width">512</media:param>
                <media:param name="height">323</media:param>
                <media:param name="allowFullScreen">true</media:param>
                <media:param name="flashVars">
                    id=12345&amp;vid=678912i&amp;lang=en-us&amp;intl=us&amp;thumbUrl=http://www.foo.com/thumbnail.jpg
                </media:param>
            </media:embed>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals('http://www.foo.com/player.swf', $media->getEmbed()->getUrl());
        $this->assertEquals(512, $media->getEmbed()->getWidth());
        $this->assertEquals(323, $media->getEmbed()->getHeight());
        $this->assertEquals(array(
            "width" => "512",
            "height" => "323",
            "type" => "application/x-shockwave-flash",
            "allowFullScreen" => "true",
            "flashVars" => "id=12345&vid=678912i&lang=en-us&intl=us&thumbUrl=http://www.foo.com/thumbnail.jpg",
        ), $media->getEmbed()->getParams());
    }

    public function testEmbedTagDefaults()
    {
        $xml = '<media:embed url="http://www.foo.com/player.swf" />';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals('http://www.foo.com/player.swf', $media->getEmbed()->getUrl());
        $this->assertEquals(null, $media->getEmbed()->getWidth());
        $this->assertEquals(null, $media->getEmbed()->getHeight());
        $this->assertEquals(array(), $media->getEmbed()->getParams());
    }

    /**
     * media:response
     *
     * Allows inclusion of a list of all media responses a media object has received.
     *
     * <media:responses>
     *   <media:response>response1</media:response>
     *   <media:response>response2</media:response>
     *   <media:response>response3</media:response>
     * </media:responses>
     */

    public function testResponsesTag()
    {
        $xml = '
            <media:responses>
                <media:response>http://www.response1.com</media:response>
                <media:response>http://www.response2.com</media:response>
            </media:responses>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(array('http://www.response1.com', 'http://www.response2.com'), $media->getResponses());
    }

    public function testResponsesTagDefaults()
    {
        $xml = '<media:responses/>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(array(), $media->getResponses());
    }

    /**
     * media:backlink
     *
     * Allows inclusion of all the URLs pointing to a media object.
     *
     * <media:backLinks>
     *   <media:backLink>http://www.backlink1.com</media:backLink>
     *   <media:backLink>http://www.backlink2.com</media:backLink>
     *   <media:backLink>http://www.backlink3.com</media:backLink>
     * </media:backLinks>
     */

    public function testBackLinksTag()
    {
        $xml = '
            <media:backLinks>
                <media:backLink>http://www.backlink1.com</media:backLink>
                <media:backLink>http://www.backlink2.com</media:backLink>
            </media:backLinks>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(array('http://www.backlink1.com', 'http://www.backlink2.com'), $media->getBacklinks());
    }

    public function testBackLinksTagDefaults()
    {
        $xml = '<media:backLinks/>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(array(), $media->getBacklinks());
    }

    /**
     * media:status
     *
     * Optional tag to specify the status of a media object -- whether it's still active or it has been blocked/deleted.
     *
     * <media:status state="blocked" reason="http://www.reasonforblocking.com" />
     *
     * state can have values "active", "blocked" or "deleted". "active" means a media object is active in the system, "blocked" means a media object is blocked by the publisher, "deleted" means a media object has been deleted by the publisher.
     *
     * reason is a reason explaining why a media object has been blocked/deleted. It can be plain text or a URL.
     */

    public function testStatusTag()
    {
        $xml = '<media:status state="blocked" reason="http://www.reasonforblocking.com" />';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(MediaStatusValue::Blocked, $media->getStatus()->getValue());
        $this->assertEquals("http://www.reasonforblocking.com", $media->getStatus()->getReason());
    }

    public function testStatusTagDefaults()
    {
        $xml = '<media:status state="active"/>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(MediaStatusValue::Active, $media->getStatus()->getValue());
        $this->assertEquals(null, $media->getStatus()->getReason());
    }

    /**
     * media:price
     *
     * Optional tag to include pricing information about a media object. If this tag is not present, the media object is supposed to be free. One media object can have multiple instances of this tag for including different pricing structures. The presence of this tag would mean that media object is not free.
     *
     * <media:price type="rent" price="19.99" currency="EUR" />
     *
     * <media:price type="package" info="http://www.dummy.jp/package_info.html" price="19.99" currency="EUR" />
     *
     * <media:price type="subscription" info="http://www.dummy.jp/subscription_info" price="19.99" currency="EUR" />
     *
     * type Valid values are "rent", "purchase", "package" or "subscription". If nothing is specified, then the media is free.
     *
     * info if the type is "package" or "subscription", then info is a URL pointing to package or subscription information. This is an optional attribute.
     *
     * price is the price of the media object. This is an optional attribute.
     *
     * currency -- use [ISO 4217] for currency codes. This is an optional attribute.
     */

    public function testPriceTag()
    {
        $xml = '
            <media:price type="rent" price="19.99" currency="EUR" />
            <media:price type="rent" price="19.99" currency="USD" />
        ';
        $media = $this->getMediaFromXMLSample($xml);

        list($price1, $price2) = $media->getPrices();

        $this->assertEquals(MediaPriceType::Rent, $price1->getType());
        $this->assertEquals(19.99, $price1->getValue());
        $this->assertEquals('EUR', $price1->getCurrency());
    }

    public function testPriceTagDefaults()
    {
        $xml = '<media:price />';
        $media = $this->getMediaFromXMLSample($xml);

        list($price) = $media->getPrices();

        $this->assertEquals(null, $price->getType());
        $this->assertEquals(null, $price->getValue());
        $this->assertEquals(null, $price->getCurrency());
    }

    /**
     * media:license
     *
     * Optional link to specify the machine-readable license associated with the content.
     *
     * <media:license type="text/html" href="http://creativecommons.org/licenses/by/3.0/us/">Creative Commons Attribution 3.0 United States License</media:license>
     */

    public function testLicenseTag()
    {
        $xml = '<media:license type="text/html" href="http://www.licensehost.com/license">Sample license for a video</media:license>';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals("Sample license for a video", $media->getLicense()->getContent());
        $this->assertEquals("http://www.licensehost.com/license", $media->getLicense()->getUrl());
        $this->assertEquals("text/html", $media->getLicense()->getType());
    }

    /**
     * media:subtitle
     *
     * Optional element for subtitle/CC link. It contains type and language attributes. Language is based on RFC 3066. There can be more than one such tag per media element, for example one per language. Please refer to Timed Text spec - W3C for more information on Timed Text and Real Time Subtitling.
     *
     * <media:subTitle type="application/smil" lang="en-us" href="http://www.example.org/subtitle.smil" />
     */

    public function testSubTitleTag()
    {
        $xml = '
            <media:subTitle type="application/smil" lang="en-us" href="http://www.foo.org/en/subtitle.smil" />
            <media:subTitle type="application/smil" lang="fr-fr" href="http://www.foo.org/fr/subtitle.smil" />
        ';
        $media = $this->getMediaFromXMLSample($xml);

        list($subtitle1, $subtitle2) = $media->getSubtitles();

        $this->assertEquals("application/smil", $subtitle1->getType());
        $this->assertEquals("en-us", $subtitle1->getLang());
        $this->assertEquals("http://www.foo.org/en/subtitle.smil", $subtitle1->getUrl());

        $this->assertEquals("application/smil", $subtitle2->getType());
        $this->assertEquals("fr-fr", $subtitle2->getLang());
        $this->assertEquals("http://www.foo.org/fr/subtitle.smil", $subtitle2->getUrl());
    }

    /**
     * media:peerlink
     *
     * Optional element for P2P link.
     *
     * <media:peerLink type="application/x-bittorrent" href="http://www.example.org/sampleFile.torrent" />
     *
     */

    public function testPeerLinkTag()
    {
        $xml = '<media:peerLink type="application/x-bittorrent" href="http://www.foo.org/sampleFile.torrent" />';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals('http://www.foo.org/sampleFile.torrent', $media->getPeerLink()->getUrl());
        $this->assertEquals('application/x-bittorrent', $media->getPeerLink()->getType());
    }

    /**
     * media:rights
     *
     * Optional element to specify the rights information of a media object.
     *
     * <media:rights status="userCreated" />
     *
     * <media:rights status="official" />
     *
     * status is the status of the media object saying whether a media object has been created by the publisher or they have rights to circulate it. Supported values are "userCreated" and "official".
     */
    public function testRightsTag()
    {
        $xml = '<media:rights status="official" />';
        $media = $this->getMediaFromXMLSample($xml);

        $this->assertEquals(MediaRightsStatus::Official, $media->getRights());
    }

    /**
     * media:scene
     *
     * Optional element to specify various scenes within a media object. It can have multiple child <media:scene> elements, where each <media:scene> element contains information about a particular scene. <media:scene> has the optional sub-elements <sceneTitle>, <sceneDescription>, <sceneStartTime> and <sceneEndTime>, which contains title, description, start and end time of a particular scene in the media, respectively.
     *
     * <media:scenes>
     *   <media:scene>
     *     <sceneTitle>sceneTitle1</sceneTitle>
     *     <sceneDescription>sceneDesc1</sceneDescription>
     *     <sceneStartTime>00:15</sceneStartTime>
     *     <sceneEndTime>00:45</sceneEndTime>
     *   </media:scene>
     *   <media:scene>
     *     <sceneTitle>sceneTitle2</sceneTitle>
     *     <sceneDescription>sceneDesc2</sceneDescription>
     *     <sceneStartTime>00:57</sceneStartTime>
     *     <sceneEndTime>01:45</sceneEndTime>
     *     </media:scene>
     * </media:scenes>
     */
    public function testScenesTag()
    {
        $xml = '
             <media:scenes>
                 <media:scene>
                     <sceneTitle>sceneTitle1</sceneTitle>
                     <sceneDescription>sceneDesc1</sceneDescription>
                     <sceneStartTime>00:15</sceneStartTime>
                     <sceneEndTime>00:45</sceneEndTime>
                 </media:scene>
                 <media:scene>
                     <sceneTitle>sceneTitle2</sceneTitle>
                     <sceneDescription>sceneDesc2</sceneDescription>
                     <sceneStartTime>00:57</sceneStartTime>
                     <sceneEndTime>01:45</sceneEndTime>
                 </media:scene>
             </media:scenes>
        ';
        $media = $this->getMediaFromXMLSample($xml);

        list($scene1, $scene2) = $media->getScenes();

        $this->assertEquals("sceneTitle1", $scene1->getTitle());
        $this->assertEquals("sceneDesc1", $scene1->getDescription());
        $this->assertEquals(new \DateTime("00:15"), $scene1->getStartTime());
        $this->assertEquals(new \DateTime("00:45"), $scene1->getEndTime());

        $this->assertEquals("sceneTitle2", $scene2->getTitle());
        $this->assertEquals("sceneDesc2", $scene2->getDescription());
        $this->assertEquals(new \DateTime("00:57"), $scene2->getStartTime());
        $this->assertEquals(new \DateTime("01:45"), $scene2->getEndTime());
    }
}
