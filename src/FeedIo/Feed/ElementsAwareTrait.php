<?php

declare(strict_types=1);

namespace FeedIo\Feed;

use ArrayIterator;
use FeedIo\Feed\Node\Element;
use FeedIo\Feed\Node\ElementInterface;
use FeedIo\Feed\Node\ElementIterator;

trait ElementsAwareTrait
{
    protected ArrayIterator $elements;

    /**
     * initialize the elements property before use
     */
    protected function initElements(): void
    {
        $this->elements = new \ArrayIterator();
    }

    /**
     * @return ElementInterface
     */
    public function newElement(): ElementInterface
    {
        return new Element();
    }

    /**
     * @param  string $name element name
     * @return ElementIterator
     */
    public function getElementIterator(string $name): ElementIterator
    {
        return new ElementIterator($this->elements, $name);
    }

    /**
     * @param  string $name element name
     * @return boolean true if the element exists
     */
    public function hasElement(string $name): bool
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
     * @return iterable
     */
    public function getAllElements(): iterable
    {
        return $this->elements;
    }

    /**
     * Returns the item's optional elements tag names
     * @return iterable
     */
    public function listElements(): iterable
    {
        foreach ($this->elements as $element) {
            yield ($element->getName());
        }
    }

    /**
     * @return \Generator
     */
    public function getElementsGenerator(): \Generator
    {
        $elements = $this->getAllElements();

        foreach ($elements as $element) {
            yield $element->getName() => $element->getValue();
        }
    }
}
