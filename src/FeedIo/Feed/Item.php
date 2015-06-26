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

class Item extends Node implements ItemInterface
{

    /**
     * @var \ArrayIterator
     */
    protected $medias;

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
}
