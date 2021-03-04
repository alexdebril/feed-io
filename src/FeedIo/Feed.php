<?php declare(strict_types=1);
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
use FeedIo\Feed\ArrayableInterface;
use FeedIo\Feed\StyleSheet;

class Feed extends Node implements FeedInterface, ArrayableInterface, \JsonSerializable
{
    /**
     * @var \ArrayIterator
     */
    protected $items;

    /**
     * @var string $url
     */
    protected $url;

    /**
     * @var string $language
     */
    protected $language;

    /**
     * @var string $logo
     */
    protected $logo;

    protected $ns;

    /**
     * @var StyleSheet
     */
    protected $styleSheet;

    public function __construct()
    {
        $this->items = new \ArrayIterator();
        $this->ns = new \ArrayIterator();

        parent::__construct();
    }

    /**
     * @return string $url
     */
    public function getUrl() : ? string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return FeedInterface
     */
    public function setUrl(string $url = null) : FeedInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string $language
     */
    public function getLanguage(): ? string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return FeedInterface
     */
    public function setLanguage(string $language = null): FeedInterface
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogo() : ? string
    {
        return $this->logo;
    }

    /**
     * @param  string $logo
     * @return NodeInterface
     */
    public function setLogo(string $logo = null) : FeedInterface
    {
        $this->logo = $logo;

        return $this;
    }

    public function setStyleSheet(StyleSheet $styleSheet): FeedInterface
    {
        $this->styleSheet = $styleSheet;

        return $this;
    }

    public function getStyleSheet(): ?StyleSheet
    {
        return $this->styleSheet;
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
        $this->items->next();
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
        $this->items->rewind();
    }

    /**
     * @param  ItemInterface $item
     * @return $this
     */
    public function add(ItemInterface $item) : FeedInterface
    {
        if ($item->getLastModified() > $this->getLastModified()) {
            $this->setLastModified($item->getLastModified());
        }
        $this->items->append($item);

        return $this;
    }

    public function addNS(string $ns, string $dtd) : FeedInterface
    {
        $this->ns->offsetSet($ns, $dtd);

        return $this;
    }

    public function getNS() : \ArrayIterator
    {
        return $this->ns;
    }

    /**
     * @return ItemInterface
     */
    public function newItem() : ItemInterface
    {
        return new Item();
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $items = [];

        foreach ($this->items as $item) {
            $items[] = $item->toArray();
        }

        $properties = parent::toArray();
        $properties['items'] = $items;

        return $properties;
    }

    /**
     * @return int
     */
    public function count() : int
    {
        return count($this->items);
    }
}
