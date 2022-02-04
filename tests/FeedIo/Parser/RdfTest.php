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
use FeedIo\Standard\Rdf;

use PHPUnit\Framework\TestCase;

class RdfTest extends ParserTestAbstract
{
    public const SAMPLE_FILE = 'sample-rdf.xml';

    /**
     * @return \FeedIo\StandardAbstract
     */
    public function getStandard()
    {
        return new Rdf(new DateTimeBuilder());
    }

    public function testParseBody()
    {
        $document = $this->buildDomDocument(static::SAMPLE_FILE);
        $feed = $this->object->parse($document, new Feed());
        $this->assertInstanceOf('\FeedIo\Feed', $feed);

        $this->assertNotEmpty($feed->getTitle(), 'title must not be empty');
        $this->assertNotEmpty($feed->getLink(), 'link must not be empty');
        $this->assertNotEmpty($feed->getLastModified(), 'lastModified must not be empty');
        $this->assertTrue($feed->valid(), 'the feed must contain an item');

        $item = $feed->current();
        $this->assertInstanceOf('\FeedIo\Feed\ItemInterface', $item);
        if ($item instanceof \FeedIo\Feed\ItemInterface) {
            $this->assertNotEmpty($item->getTitle());
            $this->assertNotEmpty($item->getContent());
            $this->assertNotEmpty($item->getLastModified());
            $this->assertNotEmpty($item->getLink());
        }
    }
}
