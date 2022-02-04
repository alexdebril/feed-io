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

use FeedIo\Feed\Item;

use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    /**
     * @var Title
     */
    protected $object;

    public const TITLE = 'my great article';

    protected function setUp(): void
    {
        $this->object = new Title();
    }

    public function testGetNodeName()
    {
        $this->assertEquals('title', $this->object->getNodeName());
    }

    public function testSet()
    {
        $item = new Item();

        $this->object->setProperty($item, new \DOMElement('title', 'feed-io title'));
        $this->assertEquals('feed-io title', $item->getTitle());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->setTitle(self::TITLE);
        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');

        $this->object->apply($document, $rootElement, $item);

        $addedElement = $rootElement->firstChild;

        $this->assertEquals(self::TITLE, $addedElement ->nodeValue);
        $this->assertEquals('title', $addedElement ->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString('<feed><title>my great article</title></feed>', $document->saveXML());
    }
}
