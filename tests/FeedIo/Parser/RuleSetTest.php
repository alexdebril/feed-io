<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser;


class RuleSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RuleSet
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new RuleSet();
    }

    public function testAdd()
    {
        $rule = $this->getMockForAbstractClass('\FeedIo\Parser\RuleInterface');
        $rule->expects($this->any())->method('getNodeName')->will($this->returnValue('mockRule'));
        $this->object->add($rule);

        $this->assertEquals($rule, $this->object->get('mockRule'));
    }

    /**
     * @expectedException \FeedIo\Parser\Rule\NotFoundException
     */
    public function testNotFoundException()
    {
        $this->object->get('unknown');
    }

}
 