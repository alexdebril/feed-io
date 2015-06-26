<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 11:04
 */
namespace FeedIo;

use FeedIo\Rule\DateTimeBuilder;

class DateRuleAbstractTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\DateRuleAbstract
     */
    protected $object;

    public function setUp()
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

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testGetDateTimeBuilderFailure()
    {
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
