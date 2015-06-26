<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26/10/14
 * Time: 00:29
 */
namespace FeedIo\Rule;

use FeedIo\Feed\Item;

class DescriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Description
     */
    protected $object;

    const DESCRIPTION = 'lorem ipsum';

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

        $this->object->setProperty($item, new \DOMElement('description', self::DESCRIPTION));
        $this->assertEquals(self::DESCRIPTION, $item->getDescription());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->setDescription(self::DESCRIPTION);

        $element = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertInstanceOf('\DomElement', $element);
        $this->assertEquals(self::DESCRIPTION, $element->nodeValue);
        $this->assertEquals('description', $element->nodeName);
    }
}
