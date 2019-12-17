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

class MediaCopyright implements MediaCopyrightInterface
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
     * @return string
     */
    public function getContent() : ?string
    {
        return $this->content;
    }

    /**
     * @param  string $content
     * @return MediaCopyrightInterface
     */
    public function setContent(?string $content) : MediaCopyrightInterface
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
     * @return MediaCopyrightInterface
     */
    public function setUrl(?string $url) : MediaCopyrightInterface
    {
        $this->url = $url;

        return $this;
    }
}
