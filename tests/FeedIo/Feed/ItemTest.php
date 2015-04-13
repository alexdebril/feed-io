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


use FeedIo\Feed\Item\Element;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FeedIo\Feed\Item
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Item();
    }

    public function testGetElement()
    {
        $element = new Element;
        $element->setName('foo');
        
        $this->object->addElement($element);
        
        $element2 = new Element;
        $element2->setName('bar');
        
        $this->object->addElement($element2);
        $iterator = $this->object->getElementIterator('foo');
        
        $this->assertTrue($iterator->count() > 0);
        
        $count = 0;
        foreach( $iterator as $element ) {
            $count++;
            $this->assertEquals('foo', $element->getName());
        }
        
        $this->assertEquals(1, $count);
    }
    
    public function testNewElement()
    {
        $this->assertInstanceOf('\FeedIo\Feed\Item\ElementInterface', $this->object->newElement());
    }
}
 