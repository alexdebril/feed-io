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

class ModifiedSinceTest extends TestCase
{
    /**
     * @var \FeedIo\Rule\ModifiedSince
     */
    protected $object;

    protected function setUp(): void
    {
        $date = new DateTimeBuilder();
        $date->addDateFormat(\DateTime::ATOM);
        $this->object = new ModifiedSince();
        $this->object->setDateTimeBuilder($date);
    }

    public function testSet()
    {
        $item = new Item();
        $date = new \DateTime('-3 days');
        $element = new \DOMElement('pubDate', $date->format(\DateTime::ATOM));
        $this->object->setProperty($item, $element);

        $this->assertEquals($date->format(\DateTime::ATOM), $item->getLastModified()->format(\DateTime::ATOM));
    }

    public function testAddedFormat()
    {
        $item = new Item();
        $dateTime = new \DateTime('-3 days');
        $element = new \DOMElement('pubDate', $dateTime->format(\DateTime::RSS));

        $dateTimeBuilder = new DateTimeBuilder();
        $dateTimeBuilder->addDateFormat(\DateTime::ATOM);
        $modifiedSince = new ModifiedSince();
        $modifiedSince->setDateTimeBuilder($dateTimeBuilder);
        $dateTimeBuilder->addDateFormat(\DateTime::RSS);

        $modifiedSince->setProperty($item, $element);
        $this->assertEquals($dateTime->format(\DateTime::ATOM), $item->getLastModified()->format(\DateTime::ATOM));
    }

    public function testGetDate()
    {
        $this->assertInstanceOf('\FeedIo\Rule\DateTimeBuilder', $this->object->getDateTimeBuilder());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $date = new \DateTime();
        $item->setLastModified($date);
        $this->object->setDefaultFormat(\DateTime::ATOM);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');

        $this->object->apply($document, $rootElement, $item);

        $addedElement = $rootElement->firstChild;

        $this->assertEquals($date->format(\DateTime::ATOM), $addedElement->nodeValue);
        $this->assertEquals('pubDate', $addedElement->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString('<feed><pubDate>'. $date->format(\DateTime::ATOM) . '</pubDate></feed>', $document->saveXML());
    }

    public function testCreateElementWithoutDateSet()
    {
        $item = new Item();
        $this->object->setDefaultFormat(\DateTime::ATOM);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');

        $this->object->apply($document, $rootElement, $item);

        $addedElement = $rootElement->firstChild;
        $this->assertEquals('pubDate', $addedElement->nodeName);
    }
}
