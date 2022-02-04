<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use FeedIo\Feed\Item;
use FeedIo\Feed\Item\MediaInterface;

use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    /**
     * @var \FeedIo\Rule\Media
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Media();
    }

    public function testSetProperty()
    {
        $document = new \DomDocument();
        $media = $document->createElement('enclosure');
        $media->setAttribute('url', 'http://localhost');
        $media->setAttribute('length', '12345');
        $media->setAttribute('type', 'audio/mp3');

        $item = new Item();
        $this->object->setProperty($item, $media);
        $this->assertTrue($item->hasMedia());

        $count = 0;
        foreach ($item->getMedias() as $itemMedia) {
            $this->assertIsString($itemMedia->getType());
            $this->assertIsString($itemMedia->getUrl());
            $this->assertIsString($itemMedia->getLength());

            $this->assertEquals($media->getAttribute('url'), $itemMedia->getUrl());
            $count++;
        }

        $this->assertEquals(1, $count);
    }

    public function testCreateElement()
    {
        $item = new Item();
        $media1 = new \FeedIo\Feed\Item\Media();

        $media1->setType('audio')
              ->setUrl('http://localhost/1')
              ->setLength('123');

        $item->addMedia($media1);

        $element = $this->object->createMediaElement(new \DomDocument(), $media1);

        $this->assertMediaEqualsElement($media1, $element);

        $media2 = new \FeedIo\Feed\Item\Media();
        $media2->setType('audio')
            ->setUrl('http://localhost/2')
            ->setLength('123');

        $item->addMedia($media2);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');

        $this->object->apply($document, $rootElement, $item);

        $firstElement = $rootElement->firstChild;
        $this->assertMediaEqualsElement($media1, $firstElement);

        $nextElement = $rootElement->lastChild;
        $this->assertMediaEqualsElement($media2, $nextElement);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString('<feed><enclosure length="123" type="audio" url="http://localhost/1"/><enclosure length="123" type="audio" url="http://localhost/2"/></feed>', $document->saveXML());
    }

    protected function assertMediaEqualsElement(MediaInterface $media, \DomElement $element)
    {
        $this->assertEquals($media->getUrl(), $element->getAttribute('url'));
        $this->assertEquals($media->getType(), $element->getAttribute('type'));
        $this->assertEquals($media->getLength(), $element->getAttribute('length'));
    }
}
