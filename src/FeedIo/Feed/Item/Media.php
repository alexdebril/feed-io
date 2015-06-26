<?php
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
    protected $type;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var int
     */
    protected $length;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param  string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param  string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param  string $length
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = intval($length);

        return $this;
    }
}
