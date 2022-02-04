<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Node;

use PHPUnit\Framework\TestCase;

class ElementIteratorTest extends TestCase
{
    /**
     * @var \FeedIo\Feed\Item\ElementIterator
     */
    protected $object;

    protected function setUp(): void
    {
        $array = new \ArrayIterator();

        $element1 = new Element();
        $element1->setName('foo');

        $element2 = new Element();
        $element2->setName('bar');

        $array->append($element1);
        $array->append($element2);

        $this->object = new ElementIterator($array, 'foo');
    }

    public function testValid()
    {
        foreach ($this->object as $element) {
            $this->assertEquals('foo', $element->getName());
        }
    }

    public function testCount()
    {
        $this->assertEquals(1, $this->object->count());

        $filter = new ElementIterator(new \ArrayIterator(), 'foo');
        $this->assertEquals(0, $filter->count());
    }
}
