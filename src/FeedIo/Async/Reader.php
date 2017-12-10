<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Async;

use \FeedIo\Adapter\Guzzle\Client;
use FeedIo\Adapter\Guzzle\Async\ReaderInterface;
use \GuzzleHttp\Promise\EachPromise;
use \FeedIo\Reader as MainReader;
use \FeedIo\Reader\Result;
use \FeedIo\FeedInterface;

class Reader implements ReaderInterface
{
    /**
     * @var \FeedIo\Reader
     */
    protected $reader;

    /**
     * @var \FeedIo\Adapter\Guzzle\Client
     */
    protected $client;

    /**
     * @var CallbackInterface
     */
    protected $callback;

    /**
     * @var string
     */
    protected $feedClass;

    /**
     * Reader constructor.
     * @param MainReader $reader
     * @param Client $client
     * @param CallbackInterface $callback
     * @param string $feedClass
     */
    public function __construct(MainReader $reader, Client $client, CallbackInterface $callback, string $feedClass)
    {
        $this->reader = $reader;
        $this->client = $client;
        $this->callback = $callback;
        $this->feedClass = $feedClass;
    }

    /**
     * @param iterable $requests
     */
    public function process(iterable $requests) : void
    {
        $promises = $this->client->getPromises($requests, $this);

        (new EachPromise($promises))->promise()->wait();
    }

    /**
     * @param Request $request
     */
    public function handle(Request $request) : void
    {
        $feed = $this->newFeed();
        $document = $this->reader->handleResponse($request->getResponse(), $feed);
        $result = new Result($document, $feed, $request->getModifiedSince(), $request->getResponse(), $request->getUrl());
        $this->callback->process($result);
    }

    /**
     * @param Request $request
     * @param \Exception $e
     */
    public function handleError(Request $request, \Exception $e) : void
    {
        $this->callback->handleError($request, $e);
    }

    /**
     * @return FeedInterface
     */
    public function newFeed() : FeedInterface
    {
        $reflection = new \ReflectionClass($this->feedClass);

        return $reflection->newInstanceArgs();
    }
}
