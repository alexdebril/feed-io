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

use FeedIo\Rule\OptionalField;

use PHPUnit\Framework\TestCase;

class RuleSetTest extends TestCase
{
    /**
     * @var RuleSet
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new RuleSet();
    }

    public function testAdd()
    {
        $rule = $this->getMockForAbstractClass('\FeedIo\RuleAbstract');
        $this->object->add($rule);

        $this->assertEquals($rule, $this->object->get('node'));
    }

    public function testAddAliases()
    {
        $name = 'main-node';
        $aliases = array('node1', 'node2');

        $this->object->addAliases($name, $aliases);
        $this->assertEquals($name, $this->object->getNameForAlias('node1'));

        $this->assertEquals($name, $this->object->getNameForAlias($name));
    }

    public function testGetByAlias()
    {
        $rule = $this->getMockForAbstractClass('\FeedIo\RuleAbstract');
        $this->object->add($rule, array('alias'));

        $this->assertEquals($rule, $this->object->get('alias'));
    }

    public function testGetRules()
    {
        $rule = $this->getMockForAbstractClass('\FeedIo\RuleAbstract');
        $this->object->add($rule);
        $rules = $this->object->getRules();
        $rules[] = new OptionalField('test');

        $this->assertCount(2, $rules, '$rules MUST have two offsets');
        $this->assertCount(1, $this->object->getRules(), '$this->object->getRules() MUST have one offset');
    }
}
