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

use FeedIo\Feed;

use PHPUnit\Framework\TestCase;

class LogoTest extends TestCase
{
    /**
     * @var Logo
     */
    protected $object;

    public const LOGO = 'http://localhost/logo.jpeg';

    protected function setUp(): void
    {
        $this->object = new Logo();
    }

    protected function appendNonEmptyChild(\DomDocument $document, \DOMElement $element, string $name, string $value = null): void
    {
        if (! is_null($value)) {
            $element->appendChild($document->createElement($name, $value));
        }
    }

    public function testSet()
    {
        $feed = new Feed();
        $document = new \DOMDocument();

        $logo = $document->createElement('image');
        $this->appendNonEmptyChild($document, $logo, 'url', self::LOGO);
        $this->appendNonEmptyChild($document, $logo, 'title', 'Dummy logo title');
        $this->appendNonEmptyChild($document, $logo, 'link', 'http://localhost');

        $this->object->setProperty($feed, $logo);
        $this->assertEquals('http://localhost/logo.jpeg', $feed->getLogo());
    }

    public function testCreateElement()
    {
        $feed = new Feed();
        $feed->setLogo(self::LOGO);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');
        $this->object->apply($document, $rootElement, $feed);

        $addedElement = $rootElement->firstChild;

        $this->assertInstanceOf('\DomElement', $addedElement);
        $this->assertEquals(self::LOGO, $addedElement->nodeValue);
        $this->assertEquals('image', $addedElement->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString(
            '<feed><image><url>http://localhost/logo.jpeg</url></image></feed>',
            $document->saveXML()
        );
    }
}
