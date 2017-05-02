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

use FeedIo\Feed\Item\Media;
use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Feed\Item\Author;
use FeedIo\Feed\Item\AuthorInterface;

class Item extends Node implements ItemInterface
{

    /**
     * @var \ArrayIterator
     */
    protected $medias;

    /**
     * @var AuthorInterface
     */
    protected $author;

    public function __construct()
    {
        $this->medias = new \ArrayIterator();

        parent::__construct();
    }

    /**
     * @param  MediaInterface $media
     * @return $this
     */
    public function addMedia(MediaInterface $media)
    {
        $this->medias->append($media);

        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @return boolean
     */
    public function hasMedia()
    {
        return $this->medias->count() > 0;
    }

    /**
     * @return MediaInterface
     */
    public function newMedia()
    {
        return new Media();
    }

    /**
     * @return AuthorInterface
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param  AuthorInterface $author
     * @return $this
     */
    public function setAuthor(AuthorInterface $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return AuthorInterface
     */
    public function newAuthor()
    {
        return new Author();
    }
}
