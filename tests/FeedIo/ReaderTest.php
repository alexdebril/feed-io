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

use Psr\Log\NullLogger;
use FeedIo\Parser\Date;

class ReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\Reader
     */
    protected $object;

    public function setUp()
    {
        $this->object = new Reader(
            $this->getClientMock(),
            new NullLogger()
        );
    }

    /**
     * @return \FeedIo\Adapter\ClientInterface
     */
    protected function getClientMock()
    {
        $client = $this->getMock('FeedIo\Adapter\ClientInterface');
        $response = $this->getMock('FeedIo\Adapter\ResponseInterface');
        $response->expects($this->any())->method('getBody')->will($this->returnValue('<rss></rss>'));
        $client->expects($this->any())->method('getResponse')->will($this->returnValue($response));

        return $client;
    }

    /**
     * @return \FeedIo\ParserAbstract
     */
    protected function getParserMock()
    {
        $parser = $this->getMockForAbstractClass(
            '\FeedIo\ParserAbstract',
            array(new Date(), new NullLogger())
        );
        $parser->expects($this->any())->method('canHandle')->will($this->returnValue(true));
        $file = dirname(__FILE__) . "/../samples/rss/sample-rss.xml";
        $domDocument = new \DOMDocument();
        $domDocument->load($file, LIBXML_NOBLANKS | LIBXML_COMPACT);
        $parser->expects($this->any())->method('getMainElement')->will($this->returnValue(
                $domDocument->documentElement->getElementsByTagName('channel')->item(0)
            ));

        return $parser;
    }

    public function testLoadDocument()
    {
        $document = $this->object->loadDocument('<foo></foo>');
        $this->assertInstanceOf('\DomDocument', $document);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testLoadMalformedDocument()
    {
        $document = $this->object->loadDocument('<foo></bar>');
        $this->assertInstanceOf('\DomDocument', $document);
    }

    public function testAddParser()
    {
        $parser = $this->getParserMock();
        $this->object->addParser($parser);
        $this->assertAttributeEquals(array($parser), 'parsers', $this->object);
    }

    public function testRead()
    {
        $feed = new Feed();
        $this->object->addParser($this->getParserMock());
        $this->object->read('fakeurl', $feed);
    }

}
 