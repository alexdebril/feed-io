<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/12/14
 * Time: 22:54
 */
namespace FeedIo\Rule;

use FeedIo\Feed\Item;
use FeedIo\Feed\Node\Element;

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
        $document = new \DomDocument();
        $element = $document->createElement('test', 'a test value');
        $element->setAttribute('foo', 'bar');

        $item = new Item();
        $this->object->setProperty($item, $element);

        $this->assertTrue($item->hasElement('test'));
        $this->assertEquals('a test value', $item->getValue('test'));
        
        $itemElements = $item->getElementIterator('test');
        
        $count = 0;
        foreach ($itemElements as $itemElement) {
            $count++;
            $this->assertEquals('bar', $itemElement->getAttribute('foo'));
        }
        
        $this->assertEquals(1, $count);
        
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->set('default', 'a test value');

        $element = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertEquals('default', $element->nodeName);
        $this->assertEquals('a test value', $element->nodeValue);
    }

    public function testCreateElementWithAttributes()
    {
        $element = new Element();
        $element->setName('default');
        $element->setValue('value');
        $element->setAttribute('foo', 'bar');

        $item = new Item();
        $item->addElement($element);

        $domElement = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertEquals('default', $domElement->nodeName);
        $this->assertEquals('value', $domElement->nodeValue);

        $this->assertTrue($domElement->hasAttribute('foo'));
    }

    public function testDontCreateElement()
    {
        $item = new Item();
        $item->set('another', 'a test value');

        $element = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertEquals('default', $element->nodeName);
        $this->assertEquals('', $element->nodeValue);
    }
}
