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

        $author->appendChild($document->createElement('name', 'John Doe'));
        $author->appendChild($document->createElement('uri', 'http://localhost'));
        $author->appendChild($document->createElement('email', 'john@localhost'));

        $this->object->setProperty($item, $author);
        $this->assertEquals('John Doe', $item->getAuthor()->getName());
        $this->assertEquals('http://localhost', $item->getAuthor()->getUri());
        $this->assertEquals('john@localhost', $item->getAuthor()->getEmail());
    }

    public function testGetChildValue()
    {
        $document = new \DOMDocument();

        $author = $document->createElement('author');
        $author->appendChild($document->createElement('name', 'John Doe'));

        $this->assertEquals('John Doe', $this->object->getChildValue($author, 'name'));
    }

    public function testCreateElement()
    {
        $item = new Item();
        $author = new \FeedIo\Feed\Item\Author;
        $author->setName('John Doe');
        $author->setUri('http://localhost');
        $author->setEmail('john@localhost');
        $item->setAuthor($author);

        $document = new \DOMDocument();
        $element = $this->object->createElement($document, $item);
        $document->appendChild($element);
        $this->assertInstanceOf('\DomElement', $element);
        $this->assertEquals('author', $element->nodeName);

        $this->assertXmlStringEqualsXmlString(
            '<?xml version="1.0"?><author><name>John Doe</name><uri>http://localhost</uri><email>john@localhost</email></author>',
            $document->saveXML());
    }
}
