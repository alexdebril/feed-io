<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;

use FeedIo\Feed\Node;
use FeedIo\Feed\Item;
use FeedIo\Feed\ItemInterface;

class Feed extends Node implements FeedInterface, \JsonSerializable, \Iterator
{
    /**
     * @var \ArrayIterator
     */
    protected $items;

    /**
     * @var string $url
     */
    protected $url;

    public function __construct()
    {
        $this->items = new \ArrayIterator();

        parent::__construct();
    }

    /**
     * @return string $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return FeedInterface
     */
    public function setUrl($url)
    {
        $this->url = $url;
        
        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->items->current();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        return $this->items->next();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->items->key();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *                 Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->items->valid();
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        return $this->items->rewind();
    }

    /**
     * @param  ItemInterface $item
     * @return $this
     */
    public function add(ItemInterface $item)
    {
        $this->items->append($item);

        return $this;
    }

    /**
     * @return ItemInterface
     */
    public function newItem()
    {
        return new Item();
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $items = [];

        foreach( $this->items as $item ) {
            $items[] = $item->toArray();
        }

        $properties = parent::toArray();
        $properties['items'] = $items;

        return $properties;
    }
}
