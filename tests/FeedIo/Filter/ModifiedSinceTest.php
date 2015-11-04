<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Filter;

use FeedIo\Feed\Item;
use FeedIo\Feed;

class ModifiedSinceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\Filter\ModifiedSince
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ModifiedSince();
        $feed = new Feed();
        $feed->setLastModified(new \DateTime('-10 days'));

        $this->object->init($feed);
    }

    public function testInit()
    {
        $feed = new Feed();
        $date = new \DateTime('-15 days');
        $feed->setLastModified($date);

        $this->object->init($feed);
        
        $this->assertAttributeEquals($date, 'date', $this->object);
    }

    public function testIsValid()
    {
        $item = new Item();
        $item->setLastModified(new \DateTime('-8 days'));
        $this->assertTrue($this->object->isValid($item));
    }

    public function testIsTooOld()
    {
        $item = new Item();
        $item->setLastModified(new \DateTime('-12 days'));
        $this->assertFalse($this->object->isValid($item));
    }

    public function testIsNotValid()
    {
        $item = new Item();
        $this->assertFalse($this->object->isValid($item));
    }
}
