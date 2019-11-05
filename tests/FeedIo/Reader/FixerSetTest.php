<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader;

use FeedIo\Feed;

use \PHPUnit\Framework\TestCase;

class FixerSetTest extends TestCase
{
    public function testCorrect()
    {
        $fixer = new FixerMock();
        $fixerSet = new FixerSet();
        $fixerSet->add($fixer);

        $feed = new Feed();
        $fixerSet->correct($feed);

        $this->assertEquals('corrected', $feed->getTitle());
    }
}
