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

interface MediaLicenseInterface
{
    /**
     * @return string
     */
    public function getContent() : ?string;

    /**
     * @param  string $content
     * @return MediaLicenseInterface
     */
    public function setContent(?string $content) : MediaLicenseInterface;


    /**
     * @return string
     */
    public function getUrl() : ?string;

    /**
     * @param  string $url
     * @return MediaLicenseInterface
     */
    public function setUrl(?string $url) : MediaLicenseInterface;


    /**
     * @return string
     */
    public function getType() : ?string;

    /**
     * @param  string $type
     * @return MediaLicenseInterface
     */
    public function setType(?string $type) : MediaLicenseInterface;
}
