<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Async;

use FeedIo\Adapter\NullClient;
use \PHPUnit\Framework\TestCase;
use \GuzzleHttp\Client as GuzzleClient;

use \FeedIo\Reader as MainReader;
use Psr\Log\NullLogger;

class ClientTest extends TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testGetPromise()
    {
        $mainReader = new MainReader(new NullClient(), new NullLogger());
        $callback = $this->getMockForAbstractClass('\FeedIo\Async\CallbackInterface');
        $client = new Client(new GuzzleClient(), new Reader($mainReader, $callback, '\FeedIo\Feed'));

        $request = $this->createMock('\FeedIo\Async\Request');
        $request->expects($this->once())->method('getUrl')->will($this->returnValue('https://packagist.org/feeds/releases.rss'));
        $request->expects($this->once())->method('getResponse')->will($this->returnValue(
            file_get_contents(dirname(__FILE__)."/../../samples/rss/expected-rss.xml")
        ));
        $requests = [$request];
        $promises = $client->getPromises($requests);

        $this->assertInstanceOf('\Generator', $promises, '$promises MUST be a generator');
        foreach ($promises as $promise) {
            $this->assertInstanceOf('\GuzzleHttp\Promise\PromiseInterface', $promise, '$promise MUST be a promise');
            $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $promise->wait(), 'the promise MUST return a PSR-7 Response');
        }
    }
}
