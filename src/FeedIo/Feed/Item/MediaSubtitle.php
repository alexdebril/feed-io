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

class MediaSubtitle
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param  string $type
     * @return MediaSubtitle
     */
    public function setType(? string $type) : MediaSubtitle
    {
        $this->type = $type;
        return $this;
    }

    public function getType() : ? string
    {
        return $this->type;
    }

    /**
     * @param  string $lang
     * @return MediaSubtitle
     */
    public function setLang(? string $lang) : MediaSubtitle
    {
        $this->lang = $lang;
        return $this;
    }

    public function getLang() : ? string
    {
        return $this->lang;
    }

    /**
     * @param  string|null url
     * @return MediaSubtitle
     */
    public function setUrl(? string $url) : MediaSubtitle
    {
        $this->url = $url;
        return $this;
    }

    public function getUrl() : ? string
    {
        return $this->url;
    }
}
