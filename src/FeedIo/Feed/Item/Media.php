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

class Media implements MediaInterface
{
    /**
     * @var string
     */
    protected $nodeName;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $length;

    /**
     * @return string
     */
    public function getNodeName() : string
    {
        return $this->nodeName;
    }

    /**
     * @param string $nodeName
     * @return MediaInterface
     */
    public function setNodeName(string $nodeName) : MediaInterface
    {
        $this->nodeName = $nodeName;

        return $this;
    }

    /**
     * @return bool
     */
    public function isThumbnail() : bool
    {
        return $this->nodeName === 'media:thumbnail';
    }

    /**
     * @return string
     */
    public function getType() : ? string
    {
        return $this->type;
    }

    /**
     * @param  string $type
     * @return MediaInterface
     */
    public function setType(?string $type) : MediaInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl() : ? string
    {
        return $this->url;
    }

    /**
     * @param  string $url
     * @return MediaInterface
     */
    public function setUrl(?string $url) : MediaInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getLength() : ? string
    {
        return $this->length;
    }

    /**
     * @param  string $length
     * @return MediaInterface
     */
    public function setLength(?string $length) : MediaInterface
    {
        $this->length = $length;

        return $this;
    }
}
