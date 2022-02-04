<?php
/**
 * Created by PhpStorm.
 * User: alex
 */

namespace FeedIo\Rule;

use FeedIo\Feed\Item;

use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    /**
     * @var \FeedIo\Rule\Author
     */
    protected $object;

    public const AUTHOR = 'John Doe';

    protected function setUp(): void
    {
        $this->object = new Author();
    }

    public function testGetNodeName()
    {
        $this->assertEquals('author', $this->object->getNodeName());
    }

    public function testSet()
    {
        $item = new Item();

        $this->object->setProperty($item, new \DOMElement('author', self::AUTHOR));
        $this->assertEquals(self::AUTHOR, $item->getAuthor()->getName());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $author = new \FeedIo\Feed\Item\Author();
        $author->setName(self::AUTHOR);
        $item->setAuthor($author);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');
        $this->object->apply($document, $rootElement, $item);

        $addedElement = $rootElement->firstChild;

        $this->assertEquals(self::AUTHOR, $addedElement ->nodeValue);
        $this->assertEquals('author', $addedElement ->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString('<feed><author>'.self::AUTHOR.'</author></feed>', $document->saveXML());
    }
}
