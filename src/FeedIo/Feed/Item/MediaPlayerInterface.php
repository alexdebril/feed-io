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

interface MediaPlayerInterface
{
    /**
     * @return string
     */
    public function getUrl() : ?string;

    /**
     * @param  string $playerUrl
     * @return MediaPlayerInterface
     */
    public function setUrl(?string $playerUrl) : MediaPlayerInterface;


    /**
     * @return int
     */
    public function getWidth() : ?int;

    /**
     * @param  string $playerWidth
     * @return MediaPlayerInterface
     */
    public function setWidth(?int $playerWidth) : MediaPlayerInterface;


    /**
     * @return int
     */
    public function getHeight() : ?int;

    /**
     * @param  string $playerHeight
     * @return MediaPlayerInterface
     */
    public function setHeight(?int $playerHeight) : MediaPlayerInterface;
}
