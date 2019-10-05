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

use FeedIo\Feed\Item;

use \PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{

    /**
     * @var Image
     */
    protected $object;

    const IMAGE = 'http://localhost/image.jpeg';

    protected function setUp()
    {
        $this->object = new Image();
    }

    public function testSet()
    {
        $item = new Item();
        $document = new \DOMDocument();

        $image = $document->createElement('logo', self::IMAGE);
        $this->object->setProperty($item, $image);
        $this->assertEquals('http://localhost/image.jpeg', $item->getImage());
    }

    public function testCreateElement()
    {
        $item = new Item();
        $item->setImage(self::IMAGE);

        $document = new \DOMDocument();
        $rootElement = $document->createElement('feed');
        $this->object->apply($document, $rootElement, $item);

        $addedElement = $rootElement->firstChild;
        $this->assertInstanceOf('\DomElement', $addedElement);
        $this->assertEquals(self::IMAGE, $addedElement->getAttribute('href'));
        $this->assertEquals('image', $addedElement->nodeName);

        $document->appendChild($rootElement);

        $this->assertXmlStringEqualsXmlString(
			'<feed><logo>http://localhost/image.jpeg</logo></feed>',
            $document->saveXML()
        );
    }
}
