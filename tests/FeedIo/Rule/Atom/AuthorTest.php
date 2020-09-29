<?php
/**
 * Created by PhpStorm.
 * User: alex
 */
namespace FeedIo\Rule\Atom;

use FeedIo\Feed\Item;

use \PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{

    /**
     * @var \FeedIo\Rule\Author
     */
    protected $object;

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

    public function testNamespacedSet()
    {
        $item = new Item();

        $ns = 'http://www.w3.org/2005/Atom';
        $impl = new \DOMImplementation();
        $document = $impl->createDocument($ns, 'feed');

        $author = $document->createElementNS($ns, 'author');

        $author->appendChild($document->createElementNS($ns, 'name', 'John Doe'));
        $author->appendChild($document->createElementNS($ns, 'uri', 'http://localhost'));
        $author->appendChild($document->createElementNS($ns, 'email', 'john@localhost'));

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
        $rootElement = $document->createElement('feed');
        $this->object->apply($document, $rootElement, $item);

        $addedElement = $rootElement->firstChild;

        $this->assertInstanceOf('\DomElement', $addedElement);
        $this->assertEquals('author', $addedElement->nodeName);
        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString(
            '<?xml version="1.0"?><feed><author><name>John Doe</name><uri>http://localhost</uri><email>john@localhost</email></author></feed>',
            $document->saveXML()
        );
    }
}
