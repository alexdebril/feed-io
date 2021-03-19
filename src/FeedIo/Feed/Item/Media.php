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

use FeedIo\Feed\ArrayableInterface;

class Media implements MediaInterface, ArrayableInterface
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
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $thumbnail;


    /**
     * @return string
     */
    public function getNodeName() : ? string
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
     * @deprecated
     * @return bool
     */
    public function isThumbnail() : bool
    {
        error_log('Method isThumbnail is deprecated and will be removed in feed-io 5.0', E_USER_DEPRECATED);
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


    /**
     * @return string
     */
    public function getTitle() : ? string
    {
        return $this->title;
    }

    /**
     * @param  string $title
     * @return MediaInterface
     */
    public function setTitle(?string $title) : MediaInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() : ? string
    {
        return $this->description;
    }

    /**
     * @param  string $description
     * @return MediaInterface
     */
    public function setDescription(?string $description) : MediaInterface
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnail() : ? string
    {
        return $this->thumbnail;
    }

    /**
     * @param  string $thumbnail
     * @return MediaInterface
     */
    public function setThumbnail(?string $thumbnail) : MediaInterface
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return get_object_vars($this);
    }
}
