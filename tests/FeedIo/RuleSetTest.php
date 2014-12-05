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
        $rule = $this->getMockForAbstractClass('\FeedIo\RuleAbstract');
        $this->object->add($rule);

        $this->assertEquals($rule, $this->object->get('node'));
    }

}
 