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

class PublicIdTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FeedIo\Reader\Fixer\PublicId
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new PublicId();
        $this->object->setLogger(new NullLogger());
    }

    public function testCorrect()
    {
        $feed = $this->getFeed();

        $this->assertNull($feed->getPublicId());
        $this->object->correct($feed);

        $this->assertEquals($feed->getLink(), $feed->getPublicId());

        foreach ($feed as $item) {
            $this->assertEquals($item->getLink(), $item->getPublicId());
        }
    }

    protected function getFeed()
    {
        $item1 = new Item();
        $item1->setLink('http://localhost/1');

        $feed = new Feed();
        $feed->add($item1)->setLink('http://localhost/');

        return $feed;
    }
}
