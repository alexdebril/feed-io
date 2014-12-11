<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 16:04
 */

namespace FeedIo\Rule\Atom;


use FeedIo\Feed\Item;

class LinkTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Link
     */
    protected $object;

    const LINK = 'http://localhost';

    protected function setUp()
    {
        $this->object = new Link();
    }

    public function testSet()
    {
        $item = new Item();
        $document = new \DOMDocument();

        $link = $document->createElement('link');
        $link->setAttribute('href', 'http://localhost');
        $this->object->setProperty($item, $link);
        $this->assertEquals('http://localhost', $item->getLink());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->setLink(self::LINK);

        $element = $this->object->createElement(new \DOMDocument(), $item);
        $this->assertInstanceOf('\DomElement', $element);
        $this->assertEquals(self::LINK, $element->getAttribute('href'));
        $this->assertEquals('link', $element->nodeName);
    }

}
