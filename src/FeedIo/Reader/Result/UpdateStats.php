<?php

declare(strict_types=1);

namespace FeedIo\Reader\Result;

use FeedIo\Feed\ItemInterface;
use FeedIo\FeedInterface;

class UpdateStats
{
    /**
     * default update delay applied when average or median intervals are outdated
     */
    public const DEFAULT_MIN_DELAY = 3600;

    /**
     * default update delay applied when the feed is considered sleepy
     */
    public const DEFAULT_SLEEPY_DELAY = 86400;

    /**
     * default duration after which the feed is considered sleepy
     */
    public const DEFAULT_DURATION_BEFORE_BEING_SLEEPY = 7 * 86400;

    /**
     * default margin ratio applied to update time calculation
     */
    public const DEFAULT_MARGIN_RATIO = 0.1;

    protected array $intervals = [];

    /**
     * UpdateStats constructor.
     * @param FeedInterface $feed
     */
    public function __construct(
        protected FeedInterface $feed
    ) {
        $this->intervals = $this->computeIntervals($this->extractDates($feed));
    }

    /**
     * @param int $minDelay
     * @param int $sleepyDelay
     * @param int $sleepyDuration
     * @param float $marginRatio
     * @return \DateTime
     */
    public function computeNextUpdate(
        int $minDelay = self::DEFAULT_MIN_DELAY,
        int $sleepyDelay = self::DEFAULT_SLEEPY_DELAY,
        int $sleepyDuration = self::DEFAULT_DURATION_BEFORE_BEING_SLEEPY,
        float $marginRatio = self::DEFAULT_MARGIN_RATIO
    ): \DateTime {
        if ($this->isSleepy($sleepyDuration, $marginRatio)) {
            return (new \DateTime())->setTimestamp(time() + $sleepyDelay);
        }
        $feedTimeStamp = $this->getFeedTimestamp();
        $now = time();
        $intervals = [
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

    /**
     * @param int $sleepyDuration
     * @param float $marginRatio
     * @return bool
     */
    public function isSleepy(int $sleepyDuration, float $marginRatio): bool
    {
        return time() > $this->addInterval($this->getFeedTimestamp(), $sleepyDuration, $marginRatio);
    }

    /**
     * @param int $ts
     * @param int $interval
     * @param float $marginRatio
     * @return int
     */
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
        return count($this->intervals) ? min($this->intervals) : 0;
    }

    /**
     * @return int
     */
    public function getMaxInterval(): int
    {
        return count($this->intervals) ? max($this->intervals) : 0;
    }

    /**
     * @return int
     */
    public function getAverageInterval(): int
    {
        $total = array_sum($this->intervals);

        return count($this->intervals) ? intval(floor($total / count($this->intervals))) : 0;
    }

    /**
     * @return int
     */
    public function getMedianInterval(): int
    {
        sort($this->intervals);
        $num = floor(count($this->intervals) / 2);

        return isset($this->intervals[$num]) ? $this->intervals[$num] : 0;
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
            $dates[] = $this->getTimestamp($item) ?? $this->getFeedTimestamp();
        }
        return $dates;
    }

    private function getTimestamp(ItemInterface $item): ?int
    {
        if (! is_null($item->getLastModified())) {
            return $item->getLastModified()->getTimestamp();
        }
        return null;
    }

    private function getFeedTimestamp(): int
    {
        return !! $this->feed->getLastModified() ? $this->feed->getLastModified()->getTimestamp() : time();
    }
}
