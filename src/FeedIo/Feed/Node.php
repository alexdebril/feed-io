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

use FeedIo\Feed\Item\Author;
use FeedIo\Feed\Item\AuthorInterface;
use FeedIo\Feed\Node\Category;
use FeedIo\Feed\Node\CategoryInterface;

class Node implements NodeInterface, ElementsAwareInterface, ArrayableInterface
{
    use ElementsAwareTrait;

    /**
     * @var AuthorInterface
     */
    protected $author;

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

    /**
     * @var string
     */
    protected $host;

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
     * @return AuthorInterface
     */
    public function getAuthor() : ? AuthorInterface
    {
        return $this->author;
    }

    /**
     * @param  AuthorInterface $author
     * @return ItemInterface
     */
    public function setAuthor(AuthorInterface $author = null) : NodeInterface
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return AuthorInterface
     */
    public function newAuthor() : AuthorInterface
    {
        return new Author();
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
    public function getHost(): ? string
    {
        return $this->host;
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
        $this->setHost($link);

        return $this;
    }

    /**
     * @param string|null $link
     */
    protected function setHost(string $link = null): void
    {
        if (!is_null($link)) {
            $this->host = '//' . parse_url($link, PHP_URL_HOST);
        }
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
        $properties['elements'] = iterator_to_array($this->getElementsGenerator());
        $properties['categories'] = iterator_to_array($this->getCategoriesGenerator());

        foreach ($properties as $name => $property) {
            if ($property instanceof \DateTime) {
                $properties[$name] = $property->format(\DateTime::ATOM);
            } elseif ($property instanceof \ArrayIterator) {
                $properties[$name] = [];
                foreach ($property as $entry) {
                    if ($entry instanceof ArrayableInterface) {
                        $entry = $entry->toArray();
                    }
                    $properties[$name] []= $entry;
                }
            } elseif ($property instanceof ArrayableInterface) {
                $properties[$name] = $property->toArray();
            }
        }

        return $properties;
    }
}
