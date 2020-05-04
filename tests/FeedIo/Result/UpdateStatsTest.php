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


use FeedIo\Feed;
use PHPUnit\Framework\TestCase;

class UpdateStatsTest extends TestCase
{

    public function testIntervals()
    {
        $feed = new Feed();
        foreach ($this->getDates() as $date) {
            $item = new Feed\Item();
            $item->setLastModified(new \DateTime($date));
            $feed->add($item);
        }

        $stats = new UpdateStats($feed);
        $intervals = $stats->getIntervals();

        $this->assertCount(4, $intervals);

        $this->assertEquals((new \DateInterval('P1D'))->days, $stats->getMinInterval()->days);
    }

    private function getDates(): array
    {
        return [
            '2020-04-01 8:00',
            '2020-04-03 8:00',
            '2020-04-10 8:00',
            '2020-04-20 8:00',
            '2020-04-21 8:00',
        ];
    }

}