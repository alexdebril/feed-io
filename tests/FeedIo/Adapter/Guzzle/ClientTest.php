<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25/01/15
 * Time: 14:47
 */
namespace FeedIo\Adapter\Guzzle;

use GuzzleHttp\Exception\BadResponseException;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use \PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    protected $body = <<<XML
<xml><feed><title>a great stream</title></feed></xml>
XML;

    /**
     * @var \FeedIo\Adapter\Guzzle\Client
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Client($this->getGuzzleClient());
    }

    public function testGetResponse()
    {
        $response = $this->object->getResponse('http://somewhere', new \DateTime());
        $this->assertInstanceOf('\FeedIo\Adapter\ResponseInterface', $response);

        $this->assertEquals($this->body, $response->getBody());
        $this->assertEquals(array(), $response->getHeaders());
        $this->assertEquals(['Tue, 15 Nov 1994 12:45:26 GMT'], $response->getHeader('name'));
        $this->assertInstanceOf('\DateTime', $response->getLastModified());
        $this->assertEquals(1994, $response->getLastModified()->format('Y'));
    }

    public function testGetNotModified()
    {
        $client = new Client($this->getClientWithStatus(304));
        $response = $client->getResponse('http://test', new \DateTime());
        $this->assertEquals(304, $response->getStatusCode());
        $this->assertEquals("", $response->getBody());
    }

    public function testGetNotFound()
    {
        $client = new Client($this->getClientWithStatus(404));
        $this->expectException('\FeedIo\Adapter\NotFoundException');
        $client->getResponse('http://test', new \DateTime());
    }

    public function testGetServerError()
    {
        $client = new Client($this->getClientWithStatus(500));
        $this->expectException('\FeedIo\Adapter\ServerErrorException');
        $client->getResponse('http://test', new \DateTime());
    }

    /**
     * @param $statusCode
     * @return \GuzzleHttp\ClientInterface
     */
    protected function getClientWithStatus($statusCode)
    {
        $stream = $this->getMockForAbstractClass('\Psr\Http\Message\StreamInterface');
        $stream->expects($this->any())->method('getContents')->will($this->returnValue(""));

        $response = $this->getMockForAbstractClass('\Psr\Http\Message\ResponseInterface');
        $response->expects($this->any())->method('getBody')->will($this->returnValue($stream));
        $response->expects($this->any())->method('getStatusCode')->will($this->returnValue($statusCode));

        $client = $this->createMock('\GuzzleHttp\ClientInterface');
        $client->expects($this->any())->method('request')->will($this->returnValue($response));

        return $client;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    protected function getGuzzleClient()
    {
        $stream = $this->getMockForAbstractClass('\Psr\Http\Message\StreamInterface');

        $stream->expects($this->any())->method('getContents')->will($this->returnValue($this->body));
        $response = $this->getMockForAbstractClass('\Psr\Http\Message\ResponseInterface');
        $response->expects($this->any())->method('getStatusCode')->will($this->returnValue(200));
        $response->expects($this->any())->method('getBody')->will($this->returnValue($stream));
        $response->expects($this->any())->method('getHeader')->will($this->returnValue(['Tue, 15 Nov 1994 12:45:26 GMT']));
        $response->expects($this->any())->method('getHeaders')->will($this->returnValue(array()));
        $response->expects($this->any())->method('hasHeader')->will($this->returnValue(true));

        $client = $this->createMock('\GuzzleHttp\ClientInterface');
        $client->expects($this->any())->method('request')->will($this->returnValue($response));

        return $client;
    }
}
