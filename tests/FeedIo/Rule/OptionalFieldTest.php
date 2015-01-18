<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/12/14
 * Time: 22:54
 */

namespace FeedIo\Rule;


use FeedIo\Feed\Item;

class OptionalFieldTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var OptionalField
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new OptionalField();
    }

    public function testSetProperty()
    {
        $element = new \DOMElement('test', 'a test value');

        $item = new Item();
        $this->object->setProperty($item, $element);

        $optionalFields = $item->getOptionalFields();
        $this->assertTrue($optionalFields->has('test'));
        $this->assertEquals('a test value', $optionalFields->get('test'));
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->getOptionalFields()->set('default', 'a test value');

        $element = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertEquals('default', $element->nodeName);
        $this->assertEquals('a test value', $element->nodeValue);
    }

    public function testDontCreateElement()
    {
        $item = new Item();
        $item->getOptionalFields()->set('another', 'a test value');

        $element = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertEquals('default', $element->nodeName);
        $this->assertEquals('', $element->nodeValue);
    }

}
