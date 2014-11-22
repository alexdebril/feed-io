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

class OptionalFieldsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FeedIo\Feed\Item\OptionalFields
     */
    protected $object;

    const NAME = 'field1';

    const VALUE = 'foobar';

    protected function setUp()
    {
        $this->object = new OptionalFields();
        $this->object->set(self::NAME, self::VALUE);
    }

    public function testSet()
    {
        $this->assertEquals(self::VALUE, $this->object->get(self::NAME));
    }

    public function testGetNull()
    {
        $this->assertNull($this->object->get('null'));
    }

    public function testHas()
    {
        $this->assertTrue($this->object->has(self::NAME));
    }

    public function testGetFields()
    {
        $this->object->set('field2', 'value2');
        $fields = $this->object->getFields();
        $this->assertEquals(array(self::NAME, 'field2'), $fields);
    }
}
