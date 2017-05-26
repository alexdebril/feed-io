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

use FeedIo\Feed\Node\Element;
use FeedIo\Feed\Node\ElementInterface;
use FeedIo\Feed\Node\ElementIterator;

trait ElementsAwareTrait
{
    /**
     * @var \ArrayIterator
     */
    protected $elements;

    /**
     * initialize the elements property before use
     */
    protected function initElements()
    {
        $this->elements = new \ArrayIterator();
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
     * @return ElementIterator
     */
    public function getElementIterator($name)
    {
        return new ElementIterator($this->elements, $name);
    }

    /**
     * @param  string $name element name
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
     * @return \Generator
     */
    public function getElementsGenerator()
    {
        $elements = $this->getAllElements();

        foreach ($elements as $element) {
            yield $element->getName() => $element->getValue();
        }
    }

}
