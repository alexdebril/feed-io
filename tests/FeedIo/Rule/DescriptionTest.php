<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26/10/14
 * Time: 00:29
 */

namespace FeedIo\Rule;

use FeedIo\Feed;
use FeedIo\Feed\Item;

use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
    /**
     * @var Description
     */
    protected $object;

    public const DESCRIPTION = 'lorem ipsum';

    public const HTML_DESCRIPTION = '<h1>a title</h1><div><p>A paragraph<a href="/link.html">a link</a></p><p>second paragraph</p></div>';

    public const HTML_DESCRIPTION_WITH_ABSOLUTE_URL = '<h1>a title</h1><div><p>A paragraph<a href="//localhost/link.html">a link</a></p><p>second paragraph</p></div>';

    protected function setUp(): void
    {
        $this->object = new Description();
    }

    public function testGetNodeName()
    {
        $this->assertEquals('description', $this->object->getNodeName());
    }

    public function testSet()
    {
        $feed = new Feed();
        $document = new \DOMDocument();
        $element = $document->createElement('description', self::DESCRIPTION);
        $document->appendChild($element);

        $this->object->setProperty($feed, $element);
        $this->assertEquals(self::DESCRIPTION, $feed->getDescription());
    }

    public function testSetWithAbsoluteUrlConversion()
    {
        $feed = new Feed();
        $feed->setLink('http://localhost/item.html');
        $document = new \DOMDocument();
        $element = $document->createElement('description', self::HTML_DESCRIPTION);
        $document->appendChild($element);

        $this->object->setProperty($feed, $element);
        $this->assertEquals(self::HTML_DESCRIPTION_WITH_ABSOLUTE_URL, $feed->getDescription());
    }

    public function testSetWithoutConversion()
    {
        $feed = new Feed();
        $feed->setLink('http://localhost/item.html');
        $document = new \DOMDocument();
        $element = $document->createElement('description', self::HTML_DESCRIPTION_WITH_ABSOLUTE_URL);
        $document->appendChild($element);

        $this->object->setProperty($feed, $element);
        $this->assertEquals(self::HTML_DESCRIPTION_WITH_ABSOLUTE_URL, $feed->getDescription());
    }


    public function testSetProperty()
    {
        $feed = new Feed();

        $document = new \DOMDocument();
        $element = $document->createElement('description', self::HTML_DESCRIPTION);
        $document->appendChild($element);

        $this->object->setProperty($feed, $element);
        $this->assertEquals(self::HTML_DESCRIPTION, $feed->getDescription());
    }

    public function testCreateElement()
    {
        $feed = new Feed();
        $feed->setDescription(self::DESCRIPTION);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');

        $this->object->apply($document, $rootElement, $feed);

        $addedElement = $rootElement->firstChild;

        $this->assertEquals(self::DESCRIPTION, $addedElement ->nodeValue);
        $this->assertEquals('description', $addedElement ->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString('<feed><description>' . self::DESCRIPTION .'</description></feed>', $document->saveXML());
    }
}
