<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule\Atom;

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

    public function testSet()
    {
        $feed = new Feed();
        $document = new \DOMDocument();

        $logo = $document->createElement('logo', self::LOGO);
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
        $this->assertEquals('logo', $addedElement->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString(
            '<feed><logo>http://localhost/logo.jpeg</logo></feed>',
            $document->saveXML()
        );
    }
}
