<?php

declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader\Result;

use FeedIo\Feed;
use PHPUnit\Framework\TestCase;

class UpdateStatsTest extends TestCase
{
    public function testIntervals()
    {
        $feed = new Feed();
        $feed->setLastModified(new \DateTime('-1 day'));
        foreach ($this->getDates() as $date) {
            $item = new Feed\Item();
            $item->setLastModified(new \DateTime($date));
            $feed->add($item);
        }

        $stats = new UpdateStats($feed);
        $intervals = $stats->getIntervals();

        $this->assertCount(4, $intervals);

        $this->assertEquals(86400, $stats->getMinInterval());
        $nextUpdate = $stats->computeNextUpdate();
        $averageInterval = $stats->getAverageInterval();
        $this->assertEquals($feed->getLastModified()->getTimestamp() + intval($averageInterval + 0.1 * $averageInterval), $nextUpdate->getTimestamp());
    }

    public function testSleepyFeed()
    {
        $feed = new Feed();
        $feed->setLastModified(new \DateTime('-10 days'));
        foreach (['-10 days', '-12 days'] as $date) {
            $item = new Feed\Item();
            $item->setLastModified(new \DateTime($date));
            $feed->add($item);
        }

        $stats = new UpdateStats($feed);
        $intervals = $stats->getIntervals();

        $this->assertCount(1, $intervals);

        $this->assertTrue($stats->isSleepy(
            UpdateStats::DEFAULT_DURATION_BEFORE_BEING_SLEEPY,
            UpdateStats::DEFAULT_MARGIN_RATIO
        ));
        $nextUpdate = $stats->computeNextUpdate();

        $this->assertEquals(time() + 86400, $nextUpdate->getTimestamp());
    }


    private function getDates(): array
    {
        return [
            '-1 day',
            '-3 days',
            '-10 days',
            '-20 days',
            '-21 days',
        ];
    }
}
