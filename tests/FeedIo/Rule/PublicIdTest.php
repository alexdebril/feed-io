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

    protected $object;

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

        $this->object->set($item, new \DOMElement('guid', 'foo'));
        $this->assertEquals('foo', $item->getPublicId());
    }
}
