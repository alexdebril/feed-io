<?php

declare(strict_types=1);

namespace FeedIo\Feed;

use FeedIo\Feed\Item\MediaInterface;

/**
 * Describes an Item instance
 *
 * an item holds three types of properties :
 * - basic values inherited from the NodeInterface like title, description, URL
 * - MediaInterface instances for medias like videos, images and podcasts
 * - ElementInterface instances for nodes not related to a known property of the ItemInterface instance
 *
 * ElementInterface instances are accessed using two methods :
 *
 * - ItemInterface::getElementIterator($name). Use it to read an array of elements or if you need to get an ElementInterface instance
 * - ItemInterface::getValue($name). use it to get the element's v    lue
 *
 */
interface ItemInterface extends NodeInterface
{
    /**
     * adds $media to the object's attributes
     *
     * @param  MediaInterface $media
     * @return ItemInterface
     */
    public function addMedia(MediaInterface $media): ItemInterface;

    /**
     * returns the current object's medias
     *
     * @return iterable
     */
    public function getMedias(): iterable;

    /**
     * returns true if at least one MediaInterface exists in the object's attributes
     *
     * @return boolean
     */
    public function hasMedia(): bool;

    /**
     * returns a new MediaInterface
     *
     * @return MediaInterface
     */
    public function newMedia(): MediaInterface;

    /**
     * Returns the item's summary. Valid for JSONFeed and Atom formats only
     *
     * @return string|null
     */
    public function getSummary(): ?string;

    /**
     * @param string|null $summary
     * @return ItemInterface
     */
    public function setSummary(string $summary = null): ItemInterface;

    /**
     * Returns the item's content. Valid for JSONFeed and Atom formats only
     *
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * @param string|null $content
     * @return ItemInterface
     */
    public function setContent(string $content = null): ItemInterface;
}
