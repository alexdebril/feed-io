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

class MediaPlayer implements MediaPlayerInterface
{
    /**
     * @var string
     */
    protected $playerUrl;

    /**
     * @var int
     */
    protected $playerWidth;

    /**
     * @var int
     */
    protected $playerHeight;

    /**
     * @return string
     */
    public function getUrl() : ?string
    {
        return $this->playerUrl;
    }

    /**
     * @param  string $playerUrl
     * @return MediaPlayerInterface
     */
    public function setUrl(?string $playerUrl) : MediaPlayerInterface
    {
        $this->playerUrl = $playerUrl;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth() : ?int
    {
        return $this->playerWidth;
    }

    /**
     * @param  int $playerWidth
     * @return MediaPlayerInterface
     */
    public function setWidth(?int $playerWidth) : MediaPlayerInterface
    {
        $this->playerWidth = $playerWidth;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight() : ?int
    {
        return $this->playerHeight;
    }

    /**
     * @param  int $playerHeight
     * @return MediaPlayerInterface
     */
    public function setHeight(?int $playerHeight) : MediaPlayerInterface
    {
        $this->playerHeight = $playerHeight;

        return $this;
    }
}
