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

use FeedIo\Rule\DateTimeBuilder;

class StandardAbstractTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\StandardAbstract
     */
    protected $object;

    public function setUp()
    {
        $date = new DateTimeBuilder();
        $date->addDateFormat(\DateTime::ATOM);
        $this->object = $this->getMockForAbstractClass(
            '\FeedIo\StandardAbstract',
            array($date)
        );
        $this->object->expects($this->any())->method('canHandle')->will($this->returnValue(true));
        $this->object->expects($this->any())->method('buildFeedRuleSet')->will($this->returnValue(new RuleSet()));
        $this->object->expects($this->any())->method('buildItemRuleSet')->will($this->returnValue(new RuleSet()));
        $this->object->expects($this->any())->method('getMainElement')->will($this->returnValue(new \DOMElement('test')));
    }

    public function testGetItemNodeName()
    {
        $this->assertInternalType('string', $this->object->getItemNodeName());
    }

    public function testGetMandatoryFields()
    {
        $this->assertInternalType('array', $this->object->getMandatoryFields());
    }

    public function testGetFeedRuleSet()
    {
        $this->assertInstanceOf('\FeedIo\RuleSet', $this->object->getFeedRuleSet());
    }

    public function testGetItemRuleSet()
    {
        $this->assertInstanceOf('\FeedIo\RuleSet', $this->object->getItemRuleSet());
    }

    public function testGetModifiedSinceRule()
    {
        $modifiedSince = $this->object->getModifiedSinceRule('pubDate');
        $this->assertInstanceOf('\FeedIo\Rule\ModifiedSince', $modifiedSince);
        $this->assertEquals('pubDate', $modifiedSince->getNodeName());
    }

    public function testBuildBaseRuleSet()
    {
        $reflection = new \ReflectionClass(get_class($this->object));
        $method = $reflection->getMethod('buildBaseRuleSet');
        $method->setAccessible(true);

        $ruleSet = $method->invoke($this->object);
        $this->assertInstanceOf('\FeedIo\RuleSet', $ruleSet);
    }
}
