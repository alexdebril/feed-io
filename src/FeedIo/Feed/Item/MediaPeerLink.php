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

class MediaPeerLink implements MediaPeerLinkInterface
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $type;

    /**
     * @return string
     */
    public function getUrl() : ?string
    {
        return $this->url;
    }

    /**
     * @param  string $url
     * @return MediaPeerLinkInterface
     */
    public function setUrl(?string $url) : MediaPeerLinkInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getType() : ?string
    {
        return $this->type;
    }

    /**
     * @param  string $type
     * @return MediaPeerLinkInterface
     */
    public function setType(?string $type) : MediaPeerLinkInterface
    {
        $this->type = $type;

        return $this;
    }
}
