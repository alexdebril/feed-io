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

use \PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    /**
     * @var \FeedIo\Feed\Item\Media
     */
    protected $object;

    protected function setUp(): void
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
        $this->assertEquals('87669', $this->object->getLength());
    }
}
