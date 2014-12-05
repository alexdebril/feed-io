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

    protected $object;

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
        $this->object->set($item, $link);
        $this->assertEquals('http://localhost', $item->getLink());
    }

}
