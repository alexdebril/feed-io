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

class RssTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\Parser\Rss
     */
    protected $object;

    public function setUp()
    {
        $this->object = new Rss(
            new DateTimeBuilder(),
            new NullLogger()
        );
    }

    public function testCanHandle()
    {
        $document = $this->buildDomDocument('rss/sample-rss.xml');
        $this->assertTrue($this->object->canHandle($document));
    }

    public function testParseBody()
    {
        $document = $this->buildDomDocument('rss/sample-rss.xml');
        $feed = $this->object->parse($document, new Feed());
        $this->assertInstanceOf('\FeedIo\Feed', $feed);

        $this->assertNotEmpty($feed->getTitle(), 'title must not be empty');
        $this->assertNotEmpty($feed->getLink(), 'link must not be empty');
        $this->assertNotEmpty($feed->getDescription(), 'description  must not be empty');
        $this->assertNotEmpty($feed->getLastModified(), 'lastModified must not be empty');

        $this->assertTrue($feed->valid());
        $item = $feed->current();
        $this->assertInstanceOf('\FeedIo\Feed\ItemInterface', $item);
        if ( $item instanceof \FeedIo\Feed\ItemInterface ){
            $this->assertNotEmpty($item->getTitle());
            $this->assertNotEmpty($item->getDescription());
            $this->assertNotEmpty($item->getPublicId());
            $this->assertNotEmpty($item->getLastModified());
            $this->assertNotEmpty($item->getLink());
            $optionalFields = $item->getOptionalFields();
            $this->assertCount(1, $optionalFields->getFields());
            $this->assertTrue($optionalFields->has('author'));
        }


    }

    /**
     * @param $filename
     * @return \DOMDocument
     */
    protected function buildDomDocument($filename)
    {
        $file = dirname(__FILE__) . "/../../samples/{$filename}";
        $domDocument = new \DOMDocument();
        $domDocument->load($file, LIBXML_NOBLANKS | LIBXML_COMPACT);

        return $domDocument;
    }
}
