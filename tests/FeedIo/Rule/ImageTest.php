<?php
/**
 * Created by PhpStorm.
 * User: alex
 */
namespace FeedIo\Rule;

use FeedIo\Feed\Item;

use \PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{

    /**
     * @var \FeedIo\Rule\Image
     */
    protected $object;

    const IMAGE = 'http://example.com/image.jpeg';

    protected function setUp()
    {
        $this->object = new Image();
    }

    public function testGetNodeName()
    {
        $this->assertEquals('image', $this->object->getNodeName());
    }

    public function testSet()
    {
        $item = new Item();

        $this->object->setProperty($item, new \DOMElement('image', self::IMAGE));
        $this->assertEquals(self::IMAGE, $item->getImage());
	}

}
