<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;

use FeedIo\Feed;

class FeedTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\Feed
     */
    protected $object;

    /**
     *
     */
    public function setUp()
    {
        $this->object = new Feed();
    }

    /**
     * @covers FeedIo\Feed::__construct
     */
    public function testConstruct()
    {
        $this->assertAttributeEquals(new \ArrayIterator(), 'items', $this->object);
    }

    public function testAdd()
    {
        $item = new Feed\Item();
        $this->object->add($item);

        $this->assertAttributeEquals(new \ArrayIterator(array($item)), 'items', $this->object);
        $this->assertEquals($this->object->current(), $item);
    }



}
 