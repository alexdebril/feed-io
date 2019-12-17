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

interface MediaPeerLinkInterface
{
    /**
     * @return string
     */
    public function getUrl() : ?string;

    /**
     * @param  string $url
     * @return MediaPeerLinkInterface
     */
    public function setUrl(?string $url) : MediaPeerLinkInterface;

    /**
     * @return string
     */
    public function getType() : ?string;

    /**
     * @param  string $type
     * @return MediaPeerLinkInterface
     */
    public function setType(?string $type) : MediaPeerLinkInterface;
}
