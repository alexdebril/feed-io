<?php

declare(strict_types=1);

namespace FeedIo\Feed;

use ArrayIterator;
use DateTime;
use Generator;
use FeedIo\Feed\Item\Author;
use FeedIo\Feed\Item\AuthorInterface;
use FeedIo\Feed\Node\Category;
use FeedIo\Feed\Node\CategoryInterface;

class Node implements NodeInterface, ElementsAwareInterface, ArrayableInterface
{
    use ElementsAwareTrait;

    protected ArrayIterator $categories;

    protected ?AuthorInterface $author = null;

    protected ?DateTime $lastModified = null;

    protected ?string $title = null;

    protected ?string $publicId = null;

    protected ?string $link = null;

    protected ?string $host = null;

    public function __construct()
    {
        $this->initElements();
        $this->categories = new ArrayIterator();
    }

    public function set(string $name, string $value = null): NodeInterface
    {
        $element = $this->newElement();

        $element->setName($name);
        $element->setValue($value);

        $this->addElement($element);

        return $this;
    }

    public function getAuthor(): ?AuthorInterface
    {
        return $this->author;
    }

    public function setAuthor(AuthorInterface $author = null): NodeInterface
    {
        $this->author = $author;

        return $this;
    }

    public function newAuthor(): AuthorInterface
    {
        return new Author();
    }

    public function getCategories(): iterable
    {
        return $this->categories;
    }

    public function getCategoriesGenerator(): Generator
    {
        foreach ($this->categories as $category) {
            yield $category->getlabel();
        }
    }

    public function addCategory(CategoryInterface $category): NodeInterface
    {
        $this->categories->append($category);

        return $this;
    }

    public function newCategory(): CategoryInterface
    {
        return new Category();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title = null): NodeInterface
    {
        $this->title = $title;

        return $this;
    }

    public function getPublicId(): ?string
    {
        return $this->publicId;
    }

    public function setPublicId(string $publicId = null): NodeInterface
    {
        $this->publicId = $publicId;

        return $this;
    }

    public function getLastModified(): ?DateTime
    {
        return $this->lastModified;
    }

    public function setLastModified(DateTime $lastModified = null): NodeInterface
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link = null): NodeInterface
    {
        $this->link = $link;
        $this->setHost($link);

        return $this;
    }

    protected function setHost(string $link = null): void
    {
        if (!is_null($link)) {
            $this->host = '//' . parse_url($link, PHP_URL_HOST);
        }
    }

    public function getValue(string $name): ?string
    {
        foreach ($this->getElementIterator($name) as $element) {
            return $element->getValue();
        }

        return null;
    }

    public function toArray(): array
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
