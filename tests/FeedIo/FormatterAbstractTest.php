<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23/11/14
 * Time: 17:44
 */

namespace FeedIo;


use FeedIo\Feed\Item;

class FormatterAbstractTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Very sneaky way to test that setItems iterates over Feed's Items
     * @see FormatterAbstractTest::testSetItems()
     * @var int
     */
    public static $ITEMS_COUNT = 0;

    /**
     * @var \FeedIo\FormatterAbstract
     */
    protected $object;

    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('\FeedIo\FormatterAbstract');
        $this->object->expects($this->any())->method('prepare')->will($this->returnArgument(0));
        $this->object->expects($this->any())->method('setHeaders')->will($this->returnSelf());
        $this->object->expects($this->any())->method('addItem')->will($this->returnCallback(
            function (){
                FormatterAbstractTest::$ITEMS_COUNT++;
            }
        ));
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
        $this->assertEquals(2, self::$ITEMS_COUNT);
    }
}
