<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader\Fixer;

use FeedIo\Feed;
use FeedIo\Feed\Item;
use Psr\Log\NullLogger;

class LastModifiedTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FeedIo\Reader\Fixer\LastModified
     */
    protected $object;

    /**
     * @var \DateTime
     */
    protected $newest;

    protected function setUp()
    {
        $this->newest = new \DateTime('2014-01-01');

        $this->object = new LastModified();
        $this->object->setLogger(new NullLogger());
    }

    public function testSearchLastModified()
    {
        $feed = $this->getFeed();

        $this->assertEquals(
            $this->newest,
            $this->object->searchLastModified($feed)
        );
    }

    public function testCorrect()
    {
        $feed = $this->getFeed();

        $this->assertNull($feed->getLastModified());
        $this->object->correct($feed);

        $this->assertEquals($this->newest, $feed->getLastModified());
    }

    protected function getFeed()
    {
        $item1 = new Item();
        $item1->setLastModified($this->newest);

        $item2 = new Item();
        $item2->setLastModified(new \DateTime('2013-01-01'));

        $feed = new Feed();
        $feed->add($item1)->add($item2);

        return $feed;
    }
}
