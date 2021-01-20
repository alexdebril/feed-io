<?php


namespace FeedIo\Rule;

use FeedIo\Feed\Item;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Image();
    }

    public function testSetProperty(): void
    {
        $document = new \DomDocument();
        $media = $document->createElement('image');
        $media->textContent = 'http://localhost';

        $item = new Item();
        $this->object->setProperty($item, $media);
        $this->assertTrue($item->hasMedia());

        $count = 0;
        foreach ($item->getMedias() as $itemMedia) {
            $this->assertIsString($itemMedia->getUrl());

            $this->assertEquals($media->textContent, $itemMedia->getUrl());
            $count++;
        }

        $this->assertEquals(1, $count);
    }
}
