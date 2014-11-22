<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed;


use FeedIo\Feed\Item\OptionalFields;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FeedIo\Feed\Item
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Item();
    }

    public function testSetOptionalFields()
    {
        $optionalFields = new OptionalFields();
        $this->object->setOptionalFields($optionalFields);
        $this->assertEquals($optionalFields, $this->object->getOptionalFields());
    }
}
 