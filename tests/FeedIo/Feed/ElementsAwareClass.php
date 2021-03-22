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

/**
 * Class ElementsAwareClass
 * @package FeedIo\Feed
 *
 * Simple class using the ElementsAwareTrait to test this trait
 */

class ElementsAwareClass
{
    use ElementsAwareTrait;

    public function __construct()
    {
        $this->initElements();
    }

    /**
     * @param  string $name element name
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
}
