<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Node;

/**
 * Describe an Element instance
 *
 * $name matches the node's tag name
 * $value matches the node's content
 * each attribute matches an attribute of the node
 *
 * for example, to represent this XML node
 *
 * <media lenght="45668" type="audio/mpeg">http://example.org/some-sound.mp3</media>
 *
 * you must set the ElementInstance's properties this way
 *
 * <code>
 * $item->setName('media');
 * $item->setValue('http://example.org/some-sound.mp3');
 * $item->setAttribute('lenght', 45668);
 * $item->setAttribute('type', 'audio/mpeg');
 *
 * </code>
 */
interface ElementInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @param  string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param  string $value
     * @return $this
     */
    public function setValue($value);

    /**
     * @param  string $name
     * @return string
     */
    public function getAttribute($name);

    /**
     * @return array
     */
    public function getAttributes();

    /**
     * @param  string $name
     * @param  string $value
     * @return $this
     */
    public function setAttribute($name, $value);

    /**
     * returns the ElementIterator to iterate over ElementInterface instances called $name
     *
     * @param  string $name element name
     * @return \FeedIo\Feed\Node\ElementIterator
     */
    public function getElementIterator($name);

    /**
     * returns true if an ElementInterface instance called $name exists
     *
     * @param  string $name element name
     * @return boolean true if the element exists
     */
    public function hasElement($name);

    /**
     * adds $element to the object's attributes
     *
     * @param  ElementInterface $element
     * @return $this
     */
    public function addElement(ElementInterface $element);

    /**
     * Returns all the item's elements
     *
     * @return \ArrayIterator
     */
    public function getAllElements();

    /**
     * Returns the item's elements tag names
     *
     * @return array
     */
    public function listElements();
}
