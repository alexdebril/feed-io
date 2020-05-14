<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader\Result;

use FeedIo\Feed\ItemInterface;
use FeedIo\FeedInterface;

class UpdateStats
{
    const DEFAULT_MIN_DELAY = 3600;

    const DEFAULT_SLEEPY_DELAY = 86400;

    const DEFAULT_MARGIN_RATIO = 0.1;

    /**
     * @var FeedInterface
     */
    protected $feed;

    /**
     * @var array
     */
    protected $intervals;

    /**
     * UpdateStats constructor.
     * @param FeedInterface $feed
     */
    public function __construct(FeedInterface $feed)
    {
        $this->feed = $feed;
        $this->intervals = $this->computeIntervals($this->extractDates($feed));
    }

    /**
     * @param int $minDelay
     * @param int $sleepyDelay
     * @param float $marginRatio
     * @return \DateTime
     */
    public function computeNextUpdate(
        int $minDelay = self::DEFAULT_MIN_DELAY,
        int $sleepyDelay = self::DEFAULT_SLEEPY_DELAY,
        float $marginRatio = self::DEFAULT_MARGIN_RATIO
    ): \DateTime {
        if ($this->isSleepy($marginRatio)) {
            return (new \DateTime())->setTimestamp(time() + $sleepyDelay);
        }
        $feedTimeStamp = $this->getFeedTimestamp();
        $now = time();
        $intervals = [
            $this->getMinInterval(),
            $this->getAverageInterval(),
            $this->getMedianInterval(),
        ];
        sort($intervals);
        $newTimestamp = $now + $minDelay;
        foreach ($intervals as $interval) {
            $computedTimestamp = $this->addInterval($feedTimeStamp, $interval, $marginRatio);
            if ($computedTimestamp > $now) {
                $newTimestamp = $computedTimestamp;
                break;
            }
        }
        return (new \DateTime())->setTimestamp($newTimestamp);
    }

    public function isSleepy(float $marginRatio): bool
    {
        return time() > $this->addInterval($this->getFeedTimestamp(), $this->getMaxInterval(), $marginRatio);
    }

    public function addInterval(int $ts, int $interval, float $marginRatio): int
    {
        return $ts + intval($interval + $marginRatio * $interval);
    }

    /**
     * @return array
     */
    public function getIntervals(): array
    {
        return $this->intervals;
    }

    /**
     * @return int
     */
    public function getMinInterval(): int
    {
        return min($this->intervals);
    }

    /**
     * @return int
     */
    public function getMaxInterval(): int
    {
        return max($this->intervals);
    }

    /**
     * @return int
     */
    public function getAverageInterval(): int
    {
        $total = array_sum($this->intervals);

        return intval(floor($total / count($this->intervals)));
    }

    /**
     * @return int
     */
    public function getMedianInterval(): int
    {
        sort($this->intervals);
        $num = floor(count($this->intervals) / 2);

        return $this->intervals[$num];
    }

    private function computeIntervals(array $dates): array
    {
        rsort($dates);
        $intervals = [];
        $current = 0;
        foreach ($dates as $date) {
            if ($current > 0) {
                $intervals[] = $current - $date;
            }
            $current = $date;
        }
        return $intervals;
    }

    private function extractDates(FeedInterface $feed): array
    {
        $dates = [];
        foreach ($feed as $item) {
            $dates[] = $this->getTimestamp($item);
        }
        return $dates;
    }

    private function getTimestamp(ItemInterface $item): ? int
    {
        return $item->getLastModified()->getTimestamp();
    }

    private function getFeedTimestamp(): int
    {
        return !! $this->feed->getLastModified() ? $this->feed->getLastModified()->getTimestamp():time();
    }
}
