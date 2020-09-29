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

use \PHPUnit\Framework\TestCase;

class StructureTest extends TestCase
{
    /**
     * @var Structure
     */
    protected $object;

    protected function setUp(): void
    {
        $ruleSet = new RuleSet();
        $ruleSet->add(new Title());
        $this->object = new Structure('foo', $ruleSet);
    }

    public function testGetNodeName()
    {
        $this->assertEquals('foo', $this->object->getNodeName());
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
        $rootElement = $document->createElement('feed');

        $this->object->apply($document, $rootElement, $item);
        $addedElement = $rootElement->firstChild;

        $this->assertEquals('foo', $addedElement ->nodeName);

        $subElement = $addedElement->firstChild;

        $this->assertEquals('foo-bar', $subElement ->nodeValue);
        $this->assertEquals('title', $subElement ->nodeName);

        $document->appendChild($rootElement);
        $node = '<feed><foo><title>foo-bar</title></foo></feed>';
        $this->assertXmlStringEqualsXmlString($node, $document->saveXML());
    }
}
