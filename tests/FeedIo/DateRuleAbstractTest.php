<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 11:04
 */

namespace FeedIo;

use FeedIo\Rule\DateTimeBuilder;

use PHPUnit\Framework\TestCase;

class DateRuleAbstractTest extends TestCase
{
    /**
     * @var \FeedIo\DateRuleAbstract
     */
    protected $object;

    public function setUp(): void
    {
        $this->object = $this->getDateRule();
    }

    public function testSetDateTimeBuilder()
    {
        $this->assertInstanceOf(
            '\FeedIo\DateRuleAbstract',
            $this->object->setDateTimeBuilder(new DateTimeBuilder())
        );
    }

    public function testGetDateTimeBuilder()
    {
        $dateTimeBuilder = new DateTimeBuilder();
        $this->object->setDateTimeBuilder($dateTimeBuilder);
        $this->assertEquals($dateTimeBuilder, $this->object->getDateTimeBuilder());
    }

    public function testGetDateTimeBuilderFailure()
    {
        $this->expectException('\UnexpectedValueException');
        $this->object->getDateTimeBuilder();
    }

    /**
     * @return \FeedIo\DateRuleAbstract
     */
    protected function getDateRule()
    {
        return $this->getMockForAbstractClass('\FeedIo\DateRuleAbstract');
    }
}
