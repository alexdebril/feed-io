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

class MediaLicense implements MediaLicenseInterface
{
    /**
     * @var string
     */
    protected $content;

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
    public function getContent() : ?string
    {
        return $this->content;
    }

    /**
     * @param  string $content
     * @return MediaLicenseInterface
     */
    public function setContent(?string $content) : MediaLicenseInterface
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() : ?string
    {
        return $this->url;
    }

    /**
     * @param  string $url
     * @return MediaLicenseInterface
     */
    public function setUrl(?string $url) : MediaLicenseInterface
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
     * @return MediaLicenseInterface
     */
    public function setType(?string $type) : MediaLicenseInterface
    {
        $this->type = $type;

        return $this;
    }
}
