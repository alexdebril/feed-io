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

use \GuzzleHttp\Client as GuzzleClient;
use \GuzzleHttp\Promise\EachPromise;
use \FeedIo\Reader as MainReader;
use \FeedIo\Reader\Result;
use \FeedIo\FeedInterface;

class Reader
{
    /**
     * @var \FeedIo\Reader
     */
    protected $reader;

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
     * @param CallbackInterface $callback
     * @param string $feedClass
     */
    public function __construct(MainReader $reader, CallbackInterface $callback, string $feedClass)
    {
        $this->reader = $reader;
        $this->callback = $callback;
        $this->feedClass = $feedClass;
    }

    /**
     * @param iterable $requests
     */
    public function process(iterable $requests) : void
    {
        $client = new Client(new GuzzleClient(), $this);
        $promises = $client->getPromises($requests);

        (new EachPromise($promises))->promise()->wait();
    }

    /**
     * @param Request $request
     */
    public function handle(Request $request)
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
