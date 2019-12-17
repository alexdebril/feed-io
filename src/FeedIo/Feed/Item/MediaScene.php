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

class MediaScene
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \DateTime
     */
    protected $startTime;

    /**
     * @var \DateTime
     */
    protected $endTime;

    /**
     * @param  string $title
     * @return MediaScene
     */
    public function setTitle(string $title) : MediaScene
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @param  string $description
     * @return MediaScene
     */
    public function setDescription(? string $description) : MediaScene
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription() : ? string
    {
        return $this->description;
    }

    /**
     * @param  \DateTime $startTime
     * @return MediaScene
     */
    public function setStartTime(? \DateTime $startTime) : MediaScene
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function getStartTime() : ? \DateTime
    {
        return $this->startTime;
    }

    /**
     * @param  \DateTime $endTime
     * @return MediaScene
     */
    public function setEndTime(? \DateTime $endTime) : MediaScene
    {
        $this->endTime = $endTime;
        return $this;
    }

    public function getEndTime() : ? \DateTime
    {
        return $this->endTime;
    }
}
