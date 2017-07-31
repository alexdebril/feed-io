<?php
/**
 * Created by PhpStorm.
 * User: alex
 */
namespace FeedIo\Rule;

use FeedIo\Feed\Item;

use \PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{

    /**
     * @var \FeedIo\Rule\Author
     */
    protected $object;

    const AUTHOR = 'John Doe';

    protected function setUp()
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
        $author = new \FeedIo\Feed\Item\Author;
        $author->setName(self::AUTHOR);
        $item->setAuthor($author);

        $element = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertInstanceOf('\DomElement', $element);
        $this->assertEquals(self::AUTHOR, $element->nodeValue);
        $this->assertEquals('author', $element->nodeName);
    }
}
