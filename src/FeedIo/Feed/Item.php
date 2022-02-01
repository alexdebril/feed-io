<?php

declare(strict_types=1);

namespace FeedIo\Feed;

use ArrayIterator;
use FeedIo\Feed\Item\Media;
use FeedIo\Feed\Item\MediaInterface;

class Item extends Node implements ItemInterface
{
    protected ArrayIterator $medias;

    protected ?string $summary = null;

    protected ?string $content = null;

    public function __construct()
    {
        $this->medias = new ArrayIterator();

        parent::__construct();
    }

    /**
     * @param  MediaInterface $media
     * @return ItemInterface
     */
    public function addMedia(MediaInterface $media): ItemInterface
    {
        $this->medias->append($media);

        return $this;
    }

    /**
     * @return iterable
     */
    public function getMedias(): iterable
    {
        return $this->medias;
    }

    /**
     * @return boolean
     */
    public function hasMedia(): bool
    {
        return $this->medias->count() > 0;
    }

    /**
     * @return MediaInterface
     */
    public function newMedia(): MediaInterface
    {
        return new Media();
    }

    /**
     * @return string|null
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @param string|null $summary
     * @return ItemInterface
     */
    public function setSummary(string $summary = null): ItemInterface
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Returns the 'content' for Atom and JSONFeed formats, 'description' for RSS
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return ItemInterface
     */
    public function setContent(string $content = null): ItemInterface
    {
        $this->content = $content;

        return $this;
    }
}
