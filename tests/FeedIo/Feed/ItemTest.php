<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed;

use FeedIo\Feed\Node\Element;
use FeedIo\Feed\Item\Media;
use FeedIo\Feed\Item\Author;

use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    /**
     * @var \FeedIo\Feed\Item
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Item();
    }

    public function testGetElementIterator()
    {
        $element = new Element();
        $element->setName('foo');

        $this->object->addElement($element);

        $element2 = new Element();
        $element2->setName('bar');

        $this->object->addElement($element2);
        $iterator = $this->object->getElementIterator('foo');

        $this->assertInstanceOf('\FeedIo\Feed\Node\ElementIterator', $iterator);
        $this->assertTrue($iterator->count() > 0);

        $count = 0;
        foreach ($iterator as $element) {
            $count++;
            $this->assertEquals('foo', $element->getName());
        }

        $this->assertEquals(1, $count);
    }

    public function testNewElement()
    {
        $this->assertInstanceOf('\FeedIo\Feed\Node\ElementInterface', $this->object->newElement());
    }

    public function testSet()
    {
        $this->object->set('foo', 'bar');
        $this->assertEquals('bar', $this->object->getValue('foo'));
    }

    public function testGetValue()
    {
        $this->assertNull($this->object->getValue('null'));
        $this->object->set('name', 'value');

        $this->assertEquals('value', $this->object->getValue('name'));
    }

    public function testHasElement()
    {
        $this->assertFalse($this->object->hasElement('foo'));
        $this->object->set('name', 'value');

        $this->assertFalse($this->object->hasElement('foo'));
        $this->assertTrue($this->object->hasElement('name'));
    }

    public function testGetAllElements()
    {
        $element = new Element();
        $element->setName('foo');

        $this->object->addElement($element);

        $element2 = new Element();
        $element2->setName('bar');

        $this->object->addElement($element2);

        $iterator = $this->object->getAllElements();

        $this->assertInstanceOf('\ArrayIterator', $iterator);
        $this->assertEquals(2, $iterator->count());
    }

    public function testListElements()
    {
        $element = new Element();
        $element->setName('foo');

        $this->object->addElement($element);

        $element2 = new Element();
        $element2->setName('bar');

        $this->object->addElement($element2);

        $elements = array();
        foreach ($this->object->listElements() as $element) {
            $elements[] = $element;
        }
        $this->assertEquals(array('foo', 'bar'), $elements);
    }

    public function testNewMedia()
    {
        $this->assertInstanceOf('\FeedIo\Feed\Item\MediaInterface', $this->object->newMedia());
    }

    public function testAddMedia()
    {
        $media = new Media();
        $media->setType('audio/mp3');

        $this->assertInstanceOf('FeedIo\Feed\Item', $this->object->addMedia($media));
    }

    public function testHasMedia()
    {
        $this->assertFalse($this->object->hasMedia());

        $this->object->addMedia(new Media());

        $this->assertTrue($this->object->hasMedia());
    }

    public function testGetMedias()
    {
        $this->object->addMedia(new Media());

        $iterator = $this->object->getMedias();
        $this->assertInstanceOf('\ArrayIterator', $iterator);
        $count = 0;
        foreach ($iterator as $media) {
            $count++;
            $this->assertInstanceOf('FeedIo\Feed\Item\MediaInterface', $media);
        }

        $this->assertEquals(1, $count);
    }

    public function testSetAuthor()
    {
        $author = new Author();
        $author->setName('test');

        $this->object->setAuthor($author);
        $this->assertEquals($author->getName(), $this->object->getAuthor()->getName());
    }

    public function testNewAuthor()
    {
        $this->assertInstanceOf('\FeedIo\Feed\Item\AuthorInterface', $this->object->newAuthor());
    }

    public function testToArray()
    {
        $author = new Author();
        $author->setName('test');
        $author->setEmail('test@example.org');
        $author->setUri('http://example.org/');
        $this->object->setAuthor($author);

        $media = new Media();
        $media->setType('audio/mp3');
        $media->setTitle('Media');
        $media->setUrl('http://example.org/media.mp3');
        $this->object->addMedia($media);

        $out = $this->object->toArray();

        $this->assertEquals(['name'  => 'test',
                             'email' => 'test@example.org',
                             'uri'   => 'http://example.org/'], $out['author']);

        $this->assertEquals([['type'  => 'audio/mp3',
                              'url'   => 'http://example.org/media.mp3',
                              'title'       => 'Media',
                              'description' => null,
                              'thumbnail'   => null,
                              'length'      => null,
                              'nodeName'    => null]], $out['medias']);
    }
}
