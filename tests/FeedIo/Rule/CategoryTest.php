<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use FeedIo\Feed\Item;

class CategoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FeedIo\Rule\Category
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Category();
    }
    
    public function testSetProperty()
    {
        $item = new Item();
        
        $element = new \DomElement('category', 'foo');
        $this->object->setProperty($item, $element);
                
        $count = 0;
        foreach ($item->getCategories() as $category) {
            $count++;
            $this->assertEquals('foo', $category->getTerm());                    
            $this->assertEquals('foo', $category->getLabel());
        }
        
        $this->assertEquals(1, $count);
    }
    
    public function testCreateCategoryElement()
    {
        $category = new \FeedIo\Feed\Node\Category();
        $category->setLabel('foo');
        $category->setScheme('bar');
        
        $element = $this->object->createCategoryElement(new \DomDocument(), $category);
        
        $this->assertEquals('foo', $element->nodeValue);
        $this->assertEquals('bar', $element->getAttribute('domain'));
    }
    
    public function testCreateCategoryElementUsingTerm()
    {
        $category = new \FeedIo\Feed\Node\Category();
        $category->setTerm('foo');
        
        $element = $this->object->createCategoryElement(new \DomDocument(), $category);
        
        $this->assertEquals('foo', $element->nodeValue);
    }
    
    public function testCreateElement()
    {
        $category = new \FeedIo\Feed\Node\Category();
        $category->setLabel('foo');
        
        $item = new Item;
        $this->assertNull($this->object->createElement(new \DomDocument, $item));
        $item->addCategory($category);
        
        $element = $this->object->createElement(new \DomDocument, $item);
        
        $this->assertEquals('foo', $element->nodeValue);
    }
    
}
