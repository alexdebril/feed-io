<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 11:28
 */
namespace FeedIo\Rule;

use FeedIo\Feed\Item;

use \PHPUnit\Framework\TestCase;

class PublicIdTest extends TestCase
{

    /**
     * @var PublicId
     */
    protected $object;

    const PUBLIC_ID = 'a12';

    protected function setUp(): void
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

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');

        $this->object->apply($document, $rootElement, $item);

        $element = $rootElement->firstChild;

        $this->assertInstanceOf('\DomElement', $element);
        $this->assertEquals(self::PUBLIC_ID, $element->nodeValue);
        $this->assertEquals('guid', $element->nodeName);
        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString('<feed><guid>a12</guid></feed>', $document->saveXML());
    }
}
