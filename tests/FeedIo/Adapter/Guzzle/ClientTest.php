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

    protected function setUp()
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

    public function testSetUserAgent()
    {
        $this->object->setUserAgent('the new user-agent');

        $this->assertAttributeEquals('the new user-agent', 'userAgent', $this->object);
    }

    /**
     * @expectedException \FeedIo\Adapter\NotFoundException
     */
    public function testGetNotFound()
    {
        $client = new Client($this->getErroredClient(404));
        $client->getResponse('http://test', new \DateTime());
    }

    /**
     * @expectedException \FeedIo\Adapter\ServerErrorException
     */
    public function testGetServerError()
    {
        $client = new Client($this->getErroredClient(500));
        $client->getResponse('http://test', new \DateTime());
    }

    /**
     * @param $statusCode
     * @return \GuzzleHttp\ClientInterface
     */
    protected function getErroredClient($statusCode)
    {
        $exception = new BadResponseException(
            'message',
            new \GuzzleHttp\Psr7\Request('get', 'http://test'),
            new \GuzzleHttp\Psr7\Response("{$statusCode}")
        );

        $guzzleClient = $this->getMockForAbstractClass('\GuzzleHttp\ClientInterface');
        $guzzleClient->expects($this->any())->method('request')->will($this->throwException($exception));

        return $guzzleClient;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    protected function getGuzzleClient()
    {
        $stream = $this->getMockForAbstractClass('\Psr\Http\Message\StreamInterface');

        $stream->expects($this->any())->method('getContents')->will($this->returnValue($this->body));
        $response = $this->getMockForAbstractClass('\Psr\Http\Message\ResponseInterface');
        $response->expects($this->any())->method('getBody')->will($this->returnValue($stream));
        $response->expects($this->any())->method('getHeader')->will($this->returnValue(['Tue, 15 Nov 1994 12:45:26 GMT']));
        $response->expects($this->any())->method('getHeaders')->will($this->returnValue(array()));
        $response->expects($this->any())->method('hasHeader')->will($this->returnValue(true));

        $client = $this->createMock('\GuzzleHttp\Client');
        $client->expects($this->any())->method('request')->will($this->returnValue($response));

        return $client;
    }


    /**
     * @runInSeparateProcess
     */
    public function testGetPromise()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar']),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(new \GuzzleHttp\Client(['handler' => $handler]));

        $request = $this->createMock('\FeedIo\Async\Request');
        $request->expects($this->once())->method('getUrl')->will($this->returnValue('https://packagist.org/feeds/releases.rss'));

        $requests = [$request];

        $reader = $this->getMockForAbstractClass('\FeedIo\Adapter\Guzzle\Async\ReaderInterface');
        $promises = $client->getPromises($requests, $reader);

        $this->assertInstanceOf('\Generator', $promises, '$promises MUST be a generator');
        foreach ($promises as $promise) {
            $this->assertInstanceOf('\GuzzleHttp\Promise\PromiseInterface', $promise, '$promise MUST be a promise');
            $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $promise->wait(), 'the promise MUST return a PSR-7 Response');
        }
    }
}
