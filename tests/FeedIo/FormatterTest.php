<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23/11/14
 * Time: 17:44
 */

namespace FeedIo;


use FeedIo\Feed\Item;
use FeedIo\Rule\DateTimeBuilder;
use Psr\Log\NullLogger;

class FormatterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\FormatterAbstract
     */
    protected $object;

    protected function setUp()
    {
        $standard = $this->getMockForAbstractClass('\FeedIo\StandardAbstract', array(new DateTimeBuilder()));
        $standard->expects($this->any())->method('format')->will($this->returnArgument(0));
        $standard->expects($this->any())->method('setHeaders')->will($this->returnSelf());
        $standard->expects($this->any())->method('buildFeedRuleSet')->will($this->returnValue(new RuleSet()));
        $standard->expects($this->any())->method('buildItemRuleSet')->will($this->returnValue(new RuleSet()));

        $this->object = new Formatter($standard, new NullLogger());
    }

    public function testGetEmptyDocument()
    {
        $this->assertInstanceOf('\DomDocument', $this->object->getEmptyDocument());
    }

    public function testGetDocument()
    {
        $this->assertInstanceOf('\DomDocument', $this->object->getDocument());
    }

    public function testToString()
    {
        $feed = new Feed();

        $this->assertInternalType('string', $this->object->toString($feed));
    }

    public function testToDom()
    {
        $feed = new Feed();

        $this->assertInstanceOf('\DomDocument', $this->object->toDom($feed));
    }

    public function testSetItems()
    {
        $feed = new Feed();
        $feed->add(new Item());
        $feed->add(new Item());

        $this->object->setItems($this->object->getDocument(), $feed);
        
    }
}
