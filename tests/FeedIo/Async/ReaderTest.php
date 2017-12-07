<?php

namespace FeedIo\Async;

use FeedIo\Adapter\NullClient;
use FeedIo\Parser\XmlParser;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Rss;
use \PHPUnit\Framework\TestCase;

use \FeedIo\Reader as MainReader;
use Psr\Log\NullLogger;

class ReaderTest extends TestCase
{
    /**
     *
     */
    public function testProcess()
    {
        $mainReader = new MainReader(new NullClient(), new NullLogger());
        $parser = new XmlParser(new Rss(new DateTimeBuilder()), new NullLogger());
        $mainReader->addParser($parser);

        $callback = $this->getMockForAbstractClass('\FeedIo\Async\CallbackInterface');
        $callback->expects($this->once())->method('process')->will($this->returnValue(true));
        $reader = new Reader($mainReader, $callback, '\FeedIo\Feed');

        $reader->process([new Request('https://packagist.org/feeds/releases.rss')]);
    }

    public function testHandleError()
    {
        $mainReader = new MainReader(new NullClient(), new NullLogger());
        $parser = new XmlParser(new Rss(new DateTimeBuilder()), new NullLogger());
        $mainReader->addParser($parser);

        $callback = $this->getMockForAbstractClass('\FeedIo\Async\CallbackInterface');
        $callback->expects($this->once())->method('handleError')->will($this->returnValue(true));
        $reader = new Reader($mainReader, $callback, '\FeedIo\Feed');

        $reader->process([new Request('/dev/null')]);
    }

    public function testFaultyCallback()
    {
        $mainReader = new MainReader(new NullClient(), new NullLogger());
        $parser = new XmlParser(new Rss(new DateTimeBuilder()), new NullLogger());
        $mainReader->addParser($parser);

        $callback = $this->getMockForAbstractClass('\FeedIo\Async\CallbackInterface');
        $callback->expects($this->once())->method('process')->will($this->throwException(new \Exception()));
        $callback->expects($this->once())->method('handleError')->will($this->returnValue(true));

        $reader = new Reader($mainReader, $callback, '\FeedIo\Feed');

        $reader->process([new Request('https://packagist.org/feeds/releases.rss')]);
    }
}
