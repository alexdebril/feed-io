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

use FeedIo\Feed\Item\Author;
use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Feed\Item\AuthorInterface;

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
    public function addMedia(MediaInterface $media) : ItemInterface;

    /**
     * returns the current object's medias
     *
     * @return iterable
     */
    public function getMedias() : iterable;

    /**
     * returns true if at least one MediaInterface exists in the object's attributes
     *
     * @return boolean
     */
    public function hasMedia() : bool;

    /**
     * returns a new MediaInterface
     *
     * @return MediaInterface
     */
    public function newMedia() : MediaInterface;

    /**
     * returns the author attribute
     *
     * @return AuthorInterface
     */
    public function getAuthor() : ? AuthorInterface;

    /**
     * sets $author to the object's attributes
     *
     * @param  AuthorInterface $author
     * @return ItemInterface
     */
    public function setAuthor(AuthorInterface $author = null) : ItemInterface;

    /**
     * returns a new AuthorInterface
     *
     * @return AuthorInterface
     */
    public function newAuthor() : AuthorInterface;
}
