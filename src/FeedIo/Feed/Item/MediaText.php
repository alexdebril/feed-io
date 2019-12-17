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

abstract class MediaTextType extends MediaConstant
{
    const Plain = 1;
    const HTML = 2;

    const VALUES = array(
        null => MediaTextType::Plain,
        "plain" => MediaTextType::Plain,
        "html" => MediaTextType::HTML,
    );
}


class MediaText
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var \DateTime
     */
    protected $start;

    /**
     * @var \DateTime
     */
    protected $end;

    /**
     * @param  string $value
     * @return MediaText
     */
    public function setValue(string $value) : MediaText
    {
        $this->value = $value;
        return $this;
    }

    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * @param  int $type
     * @return MediaText
     */
    public function setType(int $type) : MediaText
    {
        $this->type = $type;
        return $this;
    }

    public function getType() : int
    {
        return $this->type;
    }

    /**
     * @param  string $lang
     * @return MediaText
     */
    public function setLang(? string $lang) : MediaText
    {
        $this->lang = $lang;
        return $this;
    }

    public function getLang() : ? string
    {
        return $this->lang;
    }

    /**
     * @param  \DateTime $start
     * @return MediaText
     */
    public function setStart(? \DateTime $start) : MediaText
    {
        $this->start = $start;
        return $this;
    }

    public function getStart() : ? \DateTime
    {
        return $this->start;
    }

    /**
     * @param  \DateTime $end
     * @return MediaText
     */
    public function setEnd(? \DateTime $end) : MediaText
    {
        $this->end = $end;
        return $this;
    }

    public function getEnd() : ? \DateTime
    {
        return $this->end;
    }
}
