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

use FeedIo\Feed\Node\Category;

class NodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FeedIo\Feed\Node
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Node();
    }

    public function testTitle()
    {
        $title = 'my brilliant title';

        $this->assertInstanceOf('\FeedIo\Feed\Node', $this->object->setTitle($title));
        $this->assertEquals($title, $this->object->getTitle());
    }

    public function testPublicId()
    {
        $publicId = 'a12';
        $this->assertInstanceOf('\FeedIo\Feed\Node', $this->object->setPublicId($publicId));
        $this->assertEquals($publicId, $this->object->getPublicId());
    }

    public function testDescription()
    {
        $description = 'lorem ipsum';
        $this->assertInstanceOf('\FeedIo\Feed\Node', $this->object->setDescription($description));
        $this->assertEquals($description, $this->object->getDescription());
    }

    public function testLink()
    {
        $link = 'http://localhost';
        $this->assertInstanceOf('\FeedIo\Feed\Node', $this->object->setLink($link));
        $this->assertEquals($link, $this->object->getLink());
    }

    public function testLastModified()
    {
        $lastModified = new \DateTime();
        $this->assertInstanceOf('\FeedIo\Feed\Node', $this->object->setLastModified($lastModified));
        $this->assertEquals($lastModified, $this->object->getLastModified());
    }
    
    public function testNewCategory()
    {
        $this->assertInstanceOf('\FeedIo\Feed\Node\CategoryInterface', $this->object->newCategory());
    }

    public function testGetCategoryAsGenerator()
    {
        $category = new Category();
        $category->setLabel('test');

        $this->object->addCategory($category);

        $categories = $this->object->getCategoriesGenerator();

        $this->assertEquals('test', $categories->current());
    }

    public function testToArray()
    {
        $category = new Category();
        $category->setLabel('test');
        $this->object->set('foo', 'bar')
            ->setLastModified(new \DateTime())
            ->setTitle('my title')
            ->addCategory($category)
            ->setDescription('lorem ipsum');

        $out = $this->object->toArray();

        $this->assertEquals('lorem ipsum', $out['description']);
        $this->assertEquals('my title', $out['title']);
        $this->assertEquals('bar', $out['elements']['foo']);
        $this->assertEquals('test', $out['categories'][0]);
        $this->assertInternalType('string', $out['lastModified']);
    }

    public function testAddCategory()
    {
        $category = new \FeedIo\Feed\Node\Category;
        $category->setTerm('term');
        
        $this->object->addCategory($category);
        $categories = $this->object->getCategories();
        
        $count = 0;
        foreach( $categories as $testedCategory ) {
            $count++;
            $this->assertEquals('term', $testedCategory->getTerm());
            $this->assertEquals($category, $testedCategory);
        }
        
        $this->assertEquals(1, $count);
        
    }
    
}
