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

use \PHPUnit\Framework\TestCase;

class FixerSetTest extends TestCase
{
    /**
     * @var ResultMockFactory
     */
    protected $resultMockFactory;

    protected function setUp(): void
    {
        $this->resultMockFactory = new ResultMockFactory();
    }

    public function testCorrect()
    {
        $fixer = new FixerMock();
        $fixerSet = new FixerSet();
        $fixerSet->add($fixer);

        $result = $this->resultMockFactory->make();
        $feed = $result->getFeed();

        $fixerSet->correct($result);

        $this->assertEquals('corrected', $feed->getTitle());
    }
}
