<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Node;

use PHPUnit\Framework\TestCase;

class ElementTest extends TestCase
{
    /**
     * @var \FeedIo\Feed\Item\Element
     */
    protected $object;

    public function testSetName()
    {
        $element = new Element();
        $element->setName('foo');

        $this->assertEquals('foo', $element->getName());
    }

    public function testSetValue()
    {
        $element = new Element();
        $text = 'lorem ipsum';

        $element->setValue($text);
        $this->assertEquals($text, $element->getValue());
    }

    public function testAttributes()
    {
        $element = new Element();
        $element->setAttribute('url', 'http://foo.com');

        $this->assertEquals('http://foo.com', $element->getAttribute('url'));

        $this->assertEquals(array('url' => 'http://foo.com'), $element->getAttributes());
    }

    public function testGetNullAttribute()
    {
        $element = new Element();
        $this->assertNull($element->getAttribute('null'));
    }
}
