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
use FeedIo\Feed\Node\ElementInterface;

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
        /** @var Element $itemElement */
        foreach ($itemElements as $itemElement) {
            $count++;
            $this->assertEquals('bar', $itemElement->getAttribute('foo'));
        }

        $this->assertEquals(1, $count);

        $subCount = 0;
        foreach ($itemElement->getAllElements() as $subElement) {
            $count++;
        }

        $this->assertEquals(0, $subCount);
    }

    public function testSetPropertyElementWithSubElements()
    {
        $document = new \DOMDocument();
        $element = $document->createElement('test');

        $subElementValues = [
            'sub-test' => 'a test value',
            'sub-test-2' => 'a test value 2'
        ];

        foreach ($subElementValues as $name => $value) {
            $element->appendChild($document->createElement($name, $value));
        }

        $item = new Item();
        $this->object->setProperty($item, $element);

        $testElementIterator = $item->getElementIterator('test');

        $subElementCount = 0;
        /** @var ElementInterface $testElement */
        foreach ($testElementIterator as $testElement) {
            $subTestElementIterator = $testElement->getAllElements();

            if (null === $subTestElementIterator) {
                $this->fail('No sub elements found but expected');
                return;
            }

            $subTestElement = null;
            foreach ($subTestElementIterator as $subTestElement) {
                $subElementCount++;
                $expectedValue = array_shift($subElementValues);
                /** @var ElementInterface $subTestElement */
                $this->assertEquals($expectedValue, $subTestElement->getValue());
            }
        }
        $this->assertEquals(2, $subElementCount);
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->set('default', 'a test value');

        $element = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertEquals('default', $element->nodeName);
        $this->assertEquals('a test value', $element->nodeValue);
    }

    public function testCreateElementWithSubElements()
    {
        $subElement = new Element();
        $subElement->setName('subDefault');
        $subElement->setValue('defaultValue');

        $element = new Element();
        $element->setName('default');
        $element->addElement($subElement);

        $item = new Item();
        $item->addElement($element);

        $domElement = $this->object->createElement(new \DOMDocument(), $item);

        $subElementCount = 0;

        /** @var \DOMNode $childNode */
        foreach ($domElement->childNodes as $childNode) {
            if ($childNode instanceof \DOMText) {
                continue;
            }
            $subElementCount++;

            $this->assertEquals('subDefault', $childNode->nodeName);
            $this->assertEquals('defaultValue', $childNode->nodeValue);
        }

        $this->assertEquals(1, $subElementCount);
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
