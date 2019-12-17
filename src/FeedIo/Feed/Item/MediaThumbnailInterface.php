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

interface MediaThumbnailInterface
{

    /**
     * @return string
     */
    public function getUrl() : ? string;

    /**
     * @param  string $thumbnail
     * @return MediaThumbnailInterface
     */
    public function setUrl(?string $thumbnail) : MediaThumbnailInterface;

    /**
     * @return int
     */
    public function getWidth() : ?int;

    /**
     * @param  string $width
     * @return MediaThumbnailInterface
     */
    public function setWidth(?int $width) : MediaThumbnailInterface;


    /**
     * @return int
     */
    public function getHeight() : ?int;

    /**
     * @param  string $height
     * @return MediaThumbnailInterface
     */
    public function setHeight(?int $height) : MediaThumbnailInterface;


    /**
     * @return DateTime
     */
    public function getTime() : ? \DateTime;

    /**
     * @param  string $time
     * @return MediaThumbnailInterface
     */
    public function setTime(? \DateTime $time) : MediaThumbnailInterface;
}
