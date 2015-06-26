<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 11:28
 */
namespace FeedIo\Rule;

use FeedIo\Feed\Item;

class PublicIdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PublicId
     */
    protected $object;

    const PUBLIC_ID = 'a12';

    protected function setUp()
    {
        $this->object = new PublicId();
    }

    public function testGetNodeName()
    {
        $this->assertEquals('guid', $this->object->getNodeName());
    }

    public function testSet()
    {
        $item = new Item();

        $this->object->setProperty($item, new \DOMElement('guid', 'foo'));
        $this->assertEquals('foo', $item->getPublicId());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->setPublicId(self::PUBLIC_ID);

        $element = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertInstanceOf('\DomElement', $element);
        $this->assertEquals(self::PUBLIC_ID, $element->nodeValue);
        $this->assertEquals('guid', $element->nodeName);
    }
}
