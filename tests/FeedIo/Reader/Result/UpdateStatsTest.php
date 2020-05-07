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

        $this->assertEquals($feed->getLastModified()->getTimestamp() + intval(86400 + 0.1 * 86400), $nextUpdate->getTimestamp());
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
