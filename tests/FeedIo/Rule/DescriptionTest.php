<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26/10/14
 * Time: 00:29
 */
namespace FeedIo\Rule;

use FeedIo\Feed\Item;

use \PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    /**
     * @var Description
     */
    protected $object;

    const DESCRIPTION = 'lorem ipsum';

    const HTML_DESCRIPTION = '<h1>a title</h1><div><p>A paragraph</p><p>second paragraph</p></div>';

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
        $document = new \DOMDocument();
        $element = $document->createElement('description', self::DESCRIPTION);
        $document->appendChild($element);

        $this->object->setProperty($item, $element);
        $this->assertEquals(self::DESCRIPTION, $item->getDescription());
    }

    public function testSetProperty()
    {
        $item = new Item();

        $document = new \DOMDocument();
        $element = $document->createElement('description', self::HTML_DESCRIPTION);
        $document->appendChild($element);

        $this->object->setProperty($item, $element);
        $this->assertEquals(self::HTML_DESCRIPTION, $item->getDescription());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->setDescription(self::DESCRIPTION);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');

        $this->object->apply($document, $rootElement, $item);

        $addedElement = $rootElement->firstChild;

        $this->assertEquals(self::DESCRIPTION, $addedElement ->nodeValue);
        $this->assertEquals('description', $addedElement ->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString('<feed><description>' . self::DESCRIPTION .'</description></feed>', $document->saveXML());
    }
}
