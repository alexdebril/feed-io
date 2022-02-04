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
use FeedIo\Standard\Atom;

use PHPUnit\Framework\TestCase;

class AtomTest extends ParserTestAbstract
{
    public const SAMPLE_FILE = 'sample-atom.xml';

    public const ENCLOSURE_FILE = 'enclosure-atom.xml';
    /**
     * @var \FeedIo\Parser\Atom
     */
    protected $object;

    /**
     * @return \FeedIo\StandardAbstract
     */
    public function getStandard()
    {
        return new Atom(new DateTimeBuilder());
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
            $this->assertEquals('video/mpeg', $media->getType());
            $this->assertIsString($media->getUrl());
        }

        $this->assertEquals(1, $count);
    }
}
