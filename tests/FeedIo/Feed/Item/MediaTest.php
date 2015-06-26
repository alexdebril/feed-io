<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;

class MediaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FeedIo\Feed\Item\Media
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Media();
    }

    public function testSetType()
    {
        $this->object->setType('image/jpeg');
        $this->assertEquals('image/jpeg', $this->object->getType());
    }

    public function testSetLength()
    {
        $this->object->setLength('87669');
        $this->assertInternalType('integer', $this->object->getLength());
        $this->assertEquals(87669, $this->object->getLength());
    }
}
