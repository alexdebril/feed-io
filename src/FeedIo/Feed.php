<?php

declare(strict_types=1);

namespace FeedIo;

use ArrayIterator;
use FeedIo\Feed\Node;
use FeedIo\Feed\Item;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\ArrayableInterface;
use FeedIo\Feed\StyleSheet;

class Feed extends Node implements FeedInterface, ArrayableInterface, \JsonSerializable
{
    protected ArrayIterator $items;

    protected ArrayIterator $ns;

    protected ?StyleSheet $styleSheet = null;

    protected ?string $url = null;

    protected ?string $description = null;

    protected ?string $language = null;

    protected ?string $logo = null;

    public function __construct()
    {
        $this->items = new \ArrayIterator();
        $this->ns = new \ArrayIterator();

        parent::__construct();
    }

    /**
     * Returns the feed's full URL
     *
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return FeedInterface
     */
    public function setUrl(string $url = null): FeedInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description = null): FeedInterface
    {
        $this->description = $description;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language = null): FeedInterface
    {
        $this->language = $language;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo = null): FeedInterface
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

    public function current(): ItemInterface
    {
        return $this->items->current();
    }

    public function next(): void
    {
        $this->items->next();
    }

    public function key(): float|bool|int|string|null
    {
        return $this->items->key();
    }

    public function valid(): bool
    {
        return $this->items->valid();
    }

    public function rewind(): void
    {
        $this->items->rewind();
    }

    public function add(ItemInterface $item): FeedInterface
    {
        if ($item->getLastModified() > $this->getLastModified()) {
            $this->setLastModified($item->getLastModified());
        }

        $this->items->append($item);

        return $this;
    }

    public function addNS(string $ns, string $dtd): FeedInterface
    {
        $this->ns->offsetSet($ns, $dtd);

        return $this;
    }

    public function getNS(): \ArrayIterator
    {
        return $this->ns;
    }

    public function newItem(): ItemInterface
    {
        return new Item();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        $items = [];

        foreach ($this->items as $item) {
            $items[] = $item->toArray();
        }

        $properties = parent::toArray();
        $properties['items'] = $items;

        return $properties;
    }

    public function count(): int
    {
        return count($this->items);
    }
}
