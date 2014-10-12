<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser\Rule;


use FeedIo\Feed\Item;
use FeedIo\Parser\DateTimeBuilder;

class ModifiedSinceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FeedIo\Parser\Rule\ModifiedSince
     */
    protected $object;

    protected function setUp()
    {
        $date = new DateTimeBuilder();
        $date->addDateFormat(\DateTime::ATOM);
        $this->object = new ModifiedSince($date);
    }

    public function testSet()
    {
        $item = new Item();
        $date = new \DateTime('-3 days');
        $element = new \DOMElement('pubDate', $date->format(\DateTime::ATOM));
        $this->object->set($item, $element);

        $this->assertEquals($date, $item->getLastModified());
    }

    public function testAddedFormat()
    {
        $item = new Item();
        $dateTime = new \DateTime('-3 days');
        $element = new \DOMElement('pubDate', $dateTime->format(\DateTime::RSS));

        $dateTimeBuilder = new DateTimeBuilder();
        $dateTimeBuilder->addDateFormat(\DateTime::ATOM);
        $modifiedSince = new ModifiedSince($dateTimeBuilder);
        $dateTimeBuilder->addDateFormat(\DateTime::RSS);

        $modifiedSince->set($item, $element);
        $this->assertEquals($dateTime, $item->getLastModified());
    }

    public function testGetDate()
    {
        $this->assertInstanceOf('\FeedIo\Parser\DateTimeBuilder', $this->object->getDateTimeBuilder());
    }
}