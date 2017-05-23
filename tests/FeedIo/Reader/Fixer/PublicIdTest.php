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

        foreach ($feed as $idx => $item) {
            if ( 0 === $idx )
                $this->assertEquals($item->getLink(), $item->getPublicId());
            else
                $this->assertEquals('2', $item->getPublicId());
        }
    }

    protected function getFeed()
    {
        $item1 = new Item();
        $item1->setLink('http://localhost/1');

        $item2 = new Item();
        $item2->setLink('http://localhost/2');
        $item2->setPublicId('2');

        $feed = new Feed();
        $feed->add($item1)->setLink('http://localhost/');
        $feed->add($item2);

        return $feed;
    }
}
