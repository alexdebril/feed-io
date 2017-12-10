<?php

namespace FeedIo\Async;

use FeedIo\Adapter\NullClient;
use FeedIo\Parser\XmlParser;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Rss;
use \PHPUnit\Framework\TestCase;

use \FeedIo\Reader as MainReader;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

use Psr\Log\NullLogger;

class ReaderTest extends TestCase
{
    /**
     *
     */
    public function testProcess()
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['X-Foo' => 'Bar'],
                file_get_contents(dirname(__FILE__)."/../../samples/rss/expected-rss.xml")
            ),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new \FeedIo\Adapter\Guzzle\Client(new \GuzzleHttp\Client(['handler' => $handler]));
        $mainReader = new MainReader(new NullClient(), new NullLogger());
        $parser = new XmlParser(new Rss(new DateTimeBuilder()), new NullLogger());
        $mainReader->addParser($parser);

        $callback = $this->getMockForAbstractClass('\FeedIo\Async\CallbackInterface');
        $callback->expects($this->once())->method('process')->will($this->returnValue(true));
        $reader = new Reader($mainReader, $client, $callback, '\FeedIo\Feed');

        $reader->process([new Request('https://packagist.org/feeds/releases.rss')]);
    }

    public function testHandleError()
    {
        $mock = new MockHandler([
            new Response(500, ['X-Foo' => 'Bar'])
        ]);

        $handler = HandlerStack::create($mock);
        $client = new \FeedIo\Adapter\Guzzle\Client(new \GuzzleHttp\Client(['handler' => $handler]));

        $mainReader = new MainReader($client, new NullLogger());
        $parser = new XmlParser(new Rss(new DateTimeBuilder()), new NullLogger());
        $mainReader->addParser($parser);

        $callback = $this->getMockForAbstractClass('\FeedIo\Async\CallbackInterface');
        $callback->expects($this->once())->method('handleError')->will($this->returnValue(true));
        $reader = new Reader($mainReader, $client, $callback, '\FeedIo\Feed');

        $reader->process([new Request('/dev/null')]);
    }

    public function testFaultyCallback()
    {
        $mock = new MockHandler([
            new Response(
                200,
                ['X-Foo' => 'Bar'],
                    file_get_contents(dirname(__FILE__)."/../../samples/rss/expected-rss.xml")
                ),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new \FeedIo\Adapter\Guzzle\Client(new \GuzzleHttp\Client(['handler' => $handler]));

        $mainReader = new MainReader($client, new NullLogger());
        $parser = new XmlParser(new Rss(new DateTimeBuilder()), new NullLogger());
        $mainReader->addParser($parser);

        $callback = $this->getMockForAbstractClass('\FeedIo\Async\CallbackInterface');
        $callback->expects($this->once())->method('process')->will($this->throwException(new \Exception()));
        $callback->expects($this->once())->method('handleError')->will($this->returnValue(true));

        $reader = new Reader($mainReader, $client, $callback, '\FeedIo\Feed');

        $reader->process([new Request('https://packagist.org/feeds/releases.rss')]);
    }
}
