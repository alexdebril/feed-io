<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
