<?php

declare(strict_types=1);

namespace FeedIo\Feed\Node;

use Iterator;

/**
 * Iterator to filter elements by name
 * @see \FilterIterator
 */
class ElementIterator extends \FilterIterator
{
    /**
     * @param \Iterator $iterator Set of elements to filter
     * @param string    $name     Element name to accept
     */
    public function __construct(Iterator $iterator, protected string $name)
    {
        parent::__construct($iterator);
    }

    /**
     * override PHP's count implementation.
     * @return int
     */
    public function count(): int
    {
        $count = 0;
        foreach ($this as $node) {
            $count++;
        }

        return $count;
    }

    /**
     * @return boolean True if the current element's name matches the expected one
     */
    public function accept(): bool
    {
        $element = $this->getInnerIterator()->current();

        return (0 == strcasecmp($this->name, $element->getName()));
    }
}
