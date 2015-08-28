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

class CategoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FeedIo\Rule\Atom\Category
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Category();
    }
    
    public function testSetProperty()
    {
        $item = new Item();
        $document = new \DomDocument();
        $element = $document->createElement('category');

        $element->setAttribute('scheme', 'http');
        $element->setAttribute('label', 'FooBar');
        $element->setAttribute('term', 'foobar');
        $this->object->setProperty($item, $element);
                
        $count = 0;
        foreach ($item->getCategories() as $category) {
            $count++;
            $this->assertEquals('foobar', $category->getTerm());                    
            $this->assertEquals('FooBar', $category->getLabel());
            $this->assertEquals('http', $category->getScheme());
        }
        
        $this->assertEquals(1, $count);
    }
    
    public function testCreateCategoryElement()
    {
        $category = new \FeedIo\Feed\Node\Category();
        $category->setLabel('Foo');
        $category->setTerm('foo');
        $category->setScheme('bar');
        
        $element = $this->object->createCategoryElement(new \DomDocument(), $category);
        
        $this->assertEquals('Foo', $element->getAttribute('label'));        
        $this->assertEquals('foo', $element->getAttribute('term'));
        $this->assertEquals('bar', $element->getAttribute('scheme'));
    }

    public function testCreateElement()
    {
        $category = new \FeedIo\Feed\Node\Category();
        $category->setLabel('foo');
        
        $item = new Item;
        $item->addCategory($category);
        
        $element = $this->object->createElement(new \DomDocument, $item);
        
        $this->assertEquals('foo', $element->getAttribute('label'));
    }
    
}
