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

use FeedIo\Feed\Node\ElementInterface;
use FeedIo\Feed\Node\ElementIterator;

interface ElementsAwareInterface
{
    /**
     * @return ElementInterface
     */
    public function newElement() : ElementInterface;

    /**
     * @param  string $name element name
     * @return ElementIterator
     */
    public function getElementIterator(string $name) : ElementIterator;

    /**
     * @param  string $name element name
     * @return boolean true if the element exists
     */
    public function hasElement(string $name) : bool;

    /**
     * @param  ElementInterface $element
     * @return $this
     */
    public function addElement(ElementInterface $element);

    /**
     * Returns all the item's optional elements
     * @return iterable
     */
    public function getAllElements() : iterable;

    /**
     * Returns the item's optional elements tag names
     * @return iterable
     */
    public function listElements() : iterable;

    /**
     * @return \Generator
     */
    public function getElementsGenerator() : \Generator;
}
