<?php declare(strict_types=1);
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

class Node implements NodeInterface, ElementsAwareInterface
{
    use ElementsAwareTrait;

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
        $this->initElements();
        $this->categories = new \ArrayIterator();
    }

    /**
     * @param  string $name  element name
     * @param  string $value element value
     * @return NodeInterface
     */
    public function set(string $name, string $value = null) : NodeInterface
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
     * @return iterable
     */
    public function getCategories() : iterable
    {
        return $this->categories;
    }

    /**
     * @return \Generator
     */
    public function getCategoriesGenerator() : \Generator
    {
        foreach ($this->categories as $category) {
            yield $category->getlabel();
        }
    }

    /**
     * adds a category to the node
     *
     * @param \FeedIo\Feed\Node\CategoryInterface $category
     * @return NodeInterface
     */
    public function addCategory(CategoryInterface $category) : NodeInterface
    {
        $this->categories->append($category);

        return $this;
    }

    /**
     * returns a new CategoryInterface
     *
     * @return \FeedIo\Feed\Node\CategoryInterface
     */
    public function newCategory() : CategoryInterface
    {
        return new Category();
    }

    /**
     * @return string
     */
    public function getTitle() : ? string
    {
        return $this->title;
    }

    /**
     * @param  string $title
     * @return NodeInterface
     */
    public function setTitle(string $title = null) : NodeInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublicId() : ? string
    {
        return $this->publicId;
    }

    /**
     * @param  string $publicId
     * @return NodeInterface
     */
    public function setPublicId(string $publicId = null) : NodeInterface
    {
        $this->publicId = $publicId;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() : ? string
    {
        return $this->description;
    }

    /**
     * @param  string $description
     * @return NodeInterface
     */
    public function setDescription(string $description = null) : NodeInterface
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified() : ? \DateTime
    {
        return $this->lastModified;
    }

    /**
     * @param  \DateTime $lastModified
     * @return NodeInterface
     */
    public function setLastModified(\DateTime $lastModified = null) : NodeInterface
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * @return string
     */
    public function getLink() : ? string
    {
        return $this->link;
    }

    /**
     * @param  string $link
     * @return NodeInterface
     */
    public function setLink(string $link = null) : NodeInterface
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @param string $name element name
     * @return null|string
     */
    public function getValue(string $name) : ? string
    {
        foreach ($this->getElementIterator($name) as $element) {
            return $element->getValue();
        }

        return null;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $properties = get_object_vars($this);

        foreach ($properties as $name => $property) {
            if ($property instanceof \DateTime) {
                $properties[$name] = $property->format(\DateTime::ATOM);
            }
        }

        $properties['elements'] = iterator_to_array($this->getElementsGenerator());
        $properties['categories'] = iterator_to_array($this->getCategoriesGenerator());

        return $properties;
    }
}
