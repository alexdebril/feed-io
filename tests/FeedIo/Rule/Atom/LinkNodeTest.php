<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule\Atom;

use FeedIo\Feed\Item;

use PHPUnit\Framework\TestCase;

class LinkNodeTest extends TestCase
{
    /**
     * @var Link
     */
    protected $object;

    public const LINK = 'http://localhost';

    protected function setUp(): void
    {
        $this->object = new LinkNode();
    }

    public function testSet()
    {
        $item = new Item();
        $document = new \DOMDocument();

        $link = $document->createElement('link');
        $link->setAttribute('href', 'http://localhost');
        $this->object->setProperty($item, $link);
        $this->assertEquals('http://localhost', $item->getLink());
    }

    public function testSetMedia()
    {
        $item = new Item();
        $document = new \DOMDocument();

        $link = $document->createElement('link');
        $link->setAttribute('href', 'http://localhost/video.mpeg');
        $link->setAttribute('rel', 'enclosure');
        $this->object->setProperty($item, $link);

        $this->assertTrue($item->hasMedia());
        $count = 0;
        foreach ($item->getMedias() as $media) {
            $count++;
            $this->assertEquals($link->getAttribute('href'), $media->getUrl());
        }

        $this->assertEquals(1, $count);
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->setLink(self::LINK);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');
        $this->object->apply($document, $rootElement, $item);

        $addedElement = $rootElement->firstChild;

        $this->assertInstanceOf('\DomElement', $addedElement);
        $this->assertEquals(self::LINK, $addedElement->getAttribute('href'));
        $this->assertEquals('link', $addedElement->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString(
            '<feed><link href="http://localhost"/></feed>',
            $document->saveXML()
        );
    }
}
