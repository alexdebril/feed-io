<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26/10/14
 * Time: 00:29
 */

namespace FeedIo\Parser\Rule;

use FeedIo\Feed\Item;

class DescriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Description
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Description();
    }

    public function testGetNodeName()
    {
        $this->assertEquals('description', $this->object->getNodeName());
    }

    public function testSet()
    {
        $item = new Item();

        $this->object->set($item, new \DOMElement('description', 'lorem ipsum'));
        $this->assertEquals('lorem ipsum', $item->getDescription());
    }
}
