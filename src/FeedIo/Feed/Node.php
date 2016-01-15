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
use FeedIo\Feed\Node\CategoryInterface;

use FeedIo\Feed\Node\Element;
use FeedIo\Feed\Node\ElementIterator;
use FeedIo\Feed\Node\ElementInterface;

class Node implements NodeInterface
{

    /**
     * @var \ArrayIterator
     */
    protected $elements;
    
    /**
     * @var \ArrayIterator
     */
    protected $categories;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $publicId;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \DateTime
     */
    protected $lastModified;

    /**
     * @var string
     */
    protected $link;

    public function __construct()
    {
        $this->elements = new \ArrayIterator();
        $this->categories = new \ArrayIterator();
    }

    /**
     * @param  string $name  element name
     * @param  string $value element value
     * @return $this
     */
    public function set($name, $value)
    {
        $element = $this->newElement();

        $element->setName($name);
        $element->setValue($value);

        $this->addElement($element);

        return $this;
    }
    
    /**
     * returns node's categories
     *
     * @return \ArrayIterator
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * adds a category to the node
     *
     * @param \FeedIo\Feed\Node\CategoryInterface $category
     * @return $this
     */
    public function addCategory(CategoryInterface $category)
    {
        $this->categories->append($category);
        
        return $this;
    }

    /**
     * returns a new CategoryInterface
     *
     * @return \FeedIo\Feed\Node\CategoryInterface
     */
    public function newCategory()
    {
        return new Category();
    }
    
    /**
     * @return ElementInterface
     */
    public function newElement()
    {
        return new Element();
    }

    /**
     * @param  string $name element name
     * @return mixed
     */
    public function getValue($name)
    {
        foreach ($this->getElementIterator($name) as $element) {
            return $element->getValue();
        }

        return;
    }

    /**
     * @param  string          $name element name
     * @return ElementIterator
     */
    public function getElementIterator($name)
    {
        return new ElementIterator($this->elements, $name);
    }

    /**
     * @param  string  $name element name
     * @return boolean true if the element exists
     */
    public function hasElement($name)
    {
        $filter = $this->getElementIterator($name);

        return $filter->count() > 0;
    }

    /**
     * @param  ElementInterface $element
     * @return $this
     */
    public function addElement(ElementInterface $element)
    {
        $this->elements->append($element);

        return $this;
    }

    /**
     * Returns all the item's optional elements
     * @return \ArrayIterator
     */
    public function getAllElements()
    {
        return $this->elements;
    }

    /**
     * Returns the item's optional elements tag names
     * @return array
     */
    public function listElements()
    {
        foreach ($this->elements as $element) {
            yield ($element->getName());
        }
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * @param  string $publicId
     * @return $this
     */
    public function setPublicId($publicId)
    {
        $this->publicId = $publicId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param  string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param  \DateTime $lastModified
     * @return $this
     */
    public function setLastModified(\DateTime $lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param  string $link
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }
}
