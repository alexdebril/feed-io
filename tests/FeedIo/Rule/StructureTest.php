<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use FeedIo\RuleSet;
use FeedIo\Feed\Item;

class StructureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Structure
     */
    protected $object;

    protected function setUp()
    {
        $ruleSet = new RuleSet();
        $ruleSet->add(new Title());
        $this->object = new Structure('foo', $ruleSet);
    }

    public function testGetNodeName()
    {
        $this->assertEquals('foo', $this->object->getNodeName());
    }

    public function testConstruct()
    {
        $ruleSet = new RuleSet();
        $ruleSet->add(new Title());
        $structure = new Structure('foo', $ruleSet);

        $this->assertAttributeEquals($ruleSet, 'ruleSet', $structure);
    }

    public function testSet()
    {
        $document = new \DomDocument();
        $foo = $document->createElement('foo');
        $document->appendChild($foo);
        $bar = $document->createElement('title', 'hello');
        $foo->appendChild($bar);

        $item = new Item();
        $this->object->setProperty($item, $foo);
        $this->assertEquals('hello', $item->getTitle());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->setTitle('foo-bar');

        $document = new \DomDocument();

        $element = $this->object->createElement($document, $item);

        $node = '<foo><title>foo-bar</title></foo>';
        $this->assertEquals($node, $document->saveXML($element));
    }
}
