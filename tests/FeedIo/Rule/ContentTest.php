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

class ContentTest extends TestCase
{
    /**
     * @var Description
     */
    protected $object;

    const DESCRIPTION = 'lorem ipsum';

    const HTML_DESCRIPTION = '<h1>a title</h1><div><p>A paragraph<a href="/link.html">a link</a></p><p>second paragraph</p></div>';

    const HTML_DESCRIPTION_WITH_ABSOLUTE_URL = '<h1>a title</h1><div><p>A paragraph<a href="//localhost/link.html">a link</a></p><p>second paragraph</p></div>';

    protected function setUp(): void
    {
        $this->object = new Content();
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
        $this->assertEquals(self::DESCRIPTION, $item->getContent());
    }

    public function testSetWithAbsoluteUrlConversion()
    {
        $item = new Item();
        $item->setLink('http://localhost/item.html');
        $document = new \DOMDocument();
        $element = $document->createElement('description', self::HTML_DESCRIPTION);
        $document->appendChild($element);

        $this->object->setProperty($item, $element);
        $this->assertEquals(self::HTML_DESCRIPTION_WITH_ABSOLUTE_URL, $item->getContent());
    }

    public function testSetWithoutConversion()
    {
        $item = new Item();
        $item->setLink('http://localhost/item.html');
        $document = new \DOMDocument();
        $element = $document->createElement('description', self::HTML_DESCRIPTION_WITH_ABSOLUTE_URL);
        $document->appendChild($element);

        $this->object->setProperty($item, $element);
        $this->assertEquals(self::HTML_DESCRIPTION_WITH_ABSOLUTE_URL, $item->getContent());
    }


    public function testSetProperty()
    {
        $item = new Item();

        $document = new \DOMDocument();
        $element = $document->createElement('description', self::HTML_DESCRIPTION);
        $document->appendChild($element);

        $this->object->setProperty($item, $element);
        $this->assertEquals(self::HTML_DESCRIPTION, $item->getContent());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->setContent(self::DESCRIPTION);

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
