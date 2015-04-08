<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;

class ElementIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FeedIo\Feed\Item\ElementIterator
     */
    protected $object;
    
    public function testValid()
    {
        $array = new \ArrayIterator;
        
        $element1 = new Element;
        $element1->setName('foo');
        
        $element2 = new Element;
        $element2->setName('bar');
    
        $array->append($element1);
        $array->append($element2);
        
        $filter = new ElementIterator($array, 'foo');
        
        foreach( $filter as $element ) {
            $this->assertEquals('foo', $element->getName());
        }
    }
    
}
