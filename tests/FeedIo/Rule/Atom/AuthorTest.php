<?php
/**
 * Created by PhpStorm.
 * User: alex
 */
namespace FeedIo\Rule\Atom;

use FeedIo\Feed\Item;

class AuthorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\Rule\Author
     */
    protected $object;

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

        $document = new \DOMDocument();

        $author = $document->createElement('author');
        $author->setAttribute('name', 'John Doe');
        $author->setAttribute('uri', 'http://localhost');
        $author->setAttribute('email', 'john@localhost');

        $this->object->setProperty($item, $author);
        $this->assertEquals('John Doe', $item->getAuthor()->getName());
        $this->assertEquals('http://localhost', $item->getAuthor()->getUri());
        $this->assertEquals('john@localhost', $item->getAuthor()->getEmail());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $author = new \FeedIo\Feed\Item\Author;
        $author->setName('John Doe');
        $author->setUri('http://localhost');
        $author->setEmail('john@localhost');
        $item->setAuthor($author);

        $element = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertInstanceOf('\DomElement', $element);
        $this->assertEquals('author', $element->nodeName);
        $this->assertEquals('John Doe', $element->getAttribute('name'));
        $this->assertEquals('http://localhost', $element->getAttribute('uri'));
        $this->assertEquals('john@localhost', $element->getAttribute('email'));
    }
}
