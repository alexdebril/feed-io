<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;

class MediaThumbnail implements MediaThumbnailInterface
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var \DateTime
     */
    protected $time;


    /**
     * @return string
     */
    public function getUrl() : ? string
    {
        return $this->url;
    }

    /**
     * @param  string $url
     * @return MediaThumbnailInterface
     */
    public function setUrl(?string $url) : MediaThumbnailInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth() : ?int
    {
        return $this->width;
    }

    /**
     * @param  int $width
     * @return MediaThumbnailInterface
     */
    public function setWidth(?int $width) : MediaThumbnailInterface
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight() : ?int
    {
        return $this->height;
    }

    /**
     * @param  int $height
     * @return MediaThumbnailInterface
     */
    public function setHeight(?int $height) : MediaThumbnailInterface
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime() : ? \DateTime
    {
        return $this->time;
    }

    /**
     * @param  \DateTime $time
     * @return MediaThumbnailInterface
     */
    public function setTime(? \DateTime $time) : MediaThumbnailInterface
    {
        $this->time = $time;

        return $this;
    }
}
