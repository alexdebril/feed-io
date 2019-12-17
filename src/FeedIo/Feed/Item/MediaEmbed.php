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

class MediaEmbed implements MediaEmbedInterface
{
    /**
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
     * @var array
     */
    protected $params = array();

    /**
     * @return string
     */
    public function getUrl() : ?string
    {
        return $this->url;
    }

    /**
     * @param  string $url
     * @return MediaEmbedInterface
     */
    public function setUrl(?string $url) : MediaEmbedInterface
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
     * @return MediaEmbedInterface
     */
    public function setWidth(?int $width) : MediaEmbedInterface
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
     * @return MediaEmbedInterface
     */
    public function setHeight(?int $height) : MediaEmbedInterface
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * @param  array $params
     * @return MediaEmbedInterface
     */
    public function setParams(array $params) : MediaEmbedInterface
    {
        $this->params = $params;

        return $this;
    }
}
