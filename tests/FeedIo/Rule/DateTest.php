<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use \PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    /**
     * Timezone used to test a timezone switch.
     * Longyearbyen is the only place in the world where the testSetTimezone() test will fail,
     * I hope it won't bother anyone
     */
    const ALTERNATE_TIMEZONE = 'Arctic/Longyearbyen';

    /**
     * @var \FeedIo\Parser\DateTimeBuilder
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new DateTimeBuilder();
    }

    public function testGetTimezone()
    {
        $timezone = $this->object->getTimezone();
        $this->assertEquals(date_default_timezone_get(), $timezone->getName());
    }

    public function testGuessDateFormat()
    {
        $formats = array(\DateTime::ATOM, \DateTime::RFC1036);
        $this->object->setDateFormats($formats);
        $date = new \DateTime();
        $format = $this->object->guessDateFormat($date->format(\DateTime::ATOM));
        $this->assertEquals(\DateTime::ATOM, $format);
    }

    public function testDontGuessDateFormat()
    {
        $this->object->addDateFormat(\DateTime::ATOM);
        $this->assertNull($this->object->guessDateFormat('foo'));
    }

    public function testConvertDateFormat()
    {
        $formats = array(\DateTime::ATOM, \DateTime::RFC1036);
        $this->object->setDateFormats($formats);

        $date = new \DateTime('now');
        $this->assertEquals($date->format(\DateTime::ATOM), $this->object->convertToDateTime($date->format(\DateTime::ATOM))->format(\DateTime::ATOM));
        $this->assertEquals(\DateTime::ATOM, $this->object->getLastGuessedFormat());
        $this->assertEquals($date->format(\DateTime::ATOM), $this->object->convertToDateTime($date->format(\DateTime::RFC1036))->format(\DateTime::ATOM));
        $this->assertEquals(\DateTime::RFC1036, $this->object->getLastGuessedFormat());
    }

    public function testReturnDateWhenFormatIsWrong()
    {
        $this->object->addDateFormat(\DateTime::ATOM);
        $date = $this->object->convertToDateTime('foo');

        $this->assertInstanceOf('\DateTime', $date);
    }

    public function testSetTimezone()
    {
        $this->object->setTimezone(new \DateTimeZone(self::ALTERNATE_TIMEZONE));
        $this->assertEquals(self::ALTERNATE_TIMEZONE, $this->object->getTimezone()->getName());

        $this->object->addDateFormat(\DateTime::ATOM);
        $date = new \DateTime();
        $return = $this->object->convertToDateTime($date->format(\DateTime::ATOM));

        $this->assertEquals(self::ALTERNATE_TIMEZONE, $return->getTimezone()->getName());
    }
}
