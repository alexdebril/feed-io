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

class MediaTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\Rule\Media
     */
    protected $object;

    protected function setUp()
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
            $this->assertInternalType('string', $itemMedia->getType());
            $this->assertInternalType('string', $itemMedia->getUrl());
            $this->assertInternalType('integer', $itemMedia->getLength());

            $this->assertEquals($media->getAttribute('url'), $itemMedia->getUrl());
            $count++;
        }

        $this->assertEquals(1, $count);
    }

    public function testCreateElement()
    {
        $item = new Item();
        $this->assertNull($this->object->createElement(new \DomDocument(), $item));
        $media = new \FeedIo\Feed\Item\Media();

        $media->setType('audio')
              ->setUrl('http://localhost')
              ->setLength(123);

        $item->addMedia($media);

        $element = $this->object->createMediaElement(new \DomDocument(), $media);

        $this->assertMediaEqualsElement($media, $element);

        $secondElement = $this->object->createElement(new \DomDocument(), $item);
        $this->assertMediaEqualsElement($media, $element);
    }

    protected function assertMediaEqualsElement(MediaInterface $media, \DomElement $element)
    {
        $this->assertEquals($media->getUrl(), $element->getAttribute('url'));
        $this->assertEquals($media->getType(), $element->getAttribute('type'));
        $this->assertEquals($media->getLength(), $element->getAttribute('length'));
    }
}
