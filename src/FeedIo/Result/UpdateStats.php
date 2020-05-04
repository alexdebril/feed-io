<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Result;


use FeedIo\Feed\ItemInterface;
use FeedIo\FeedInterface;

class UpdateStats
{

    private $intervals;

    public function __construct(FeedInterface $feed)
    {
        $this->intervals = $this->computeIntervals($this->extractDates($feed));
    }

    /**
     * @return array
     */
    public function getIntervals(): array
    {
        return $this->intervals;
    }

    public function getMinInterval(): \DateInterval
    {
        $value = min($this->intervals);
        return new \DateInterval("PT{$value}S");
    }

    private function computeIntervals(array $dates): array
    {
        rsort($dates);
        $intervals = [];
        $current = 0;
        foreach ($dates as $date) {
            if ( $current > 0) {
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
}