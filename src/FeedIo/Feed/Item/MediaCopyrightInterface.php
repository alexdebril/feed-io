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

interface MediaCopyrightInterface
{
    /**
     * @return string
     */
    public function getContent() : ?string;

    /**
     * @param  string $copyright
     * @return MediaCopyrightInterface
     */
    public function setContent(?string $copyright) : MediaCopyrightInterface;

    /**
     * @return string
     */
    public function getUrl() : ?string;

    /**
     * @param  string $url
     * @return MediaCopyrightInterface
     */
    public function setUrl(?string $url) : MediaCopyrightInterface;
}
