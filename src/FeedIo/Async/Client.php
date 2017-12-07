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

use \GuzzleHttp\ClientInterface;
use \GuzzleHttp\Promise\PromiseInterface;
use \FeedIo\Adapter\Guzzle\Response;

class Client
{

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $guzzleClient;

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * Client constructor.
     * @param ClientInterface $guzzleClient
     * @param Reader $reader
     */
    public function __construct(ClientInterface $guzzleClient, Reader $reader)
    {
        $this->guzzleClient = $guzzleClient;
        $this->reader = $reader;
    }

    /**
     * @param iterable $requests
     * @return \Generator
     */
    public function getPromises(iterable $requests) : \Generator
    {
        foreach ($requests as $request) {
            yield $this->getPromise($request);
        }
    }

    /**
     * @param Request $request
     * @return PromiseInterface
     */
    protected function getPromise(Request $request) : PromiseInterface
    {
        $promise = $this->newPromise($request);
        $reader= $this->reader;

        $promise->then(function ($psrResponse) use ($request, $reader) {
            try {
                $request->setResponse(new Response($psrResponse));
                $reader->handle($request);
            } catch (\Exception $e) {
                $reader->handleError($request, $e);
            }
        }, function ($error) use ($request, $reader) {
            $reader->handleError($request, $error);
        });

        return $promise;
    }

    /**
     * @param Request $request
     * @return PromiseInterface
     */
    protected function newPromise(Request $request) : PromiseInterface
    {
        $options = [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (X11; U; Linux i686; fr; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1',
                'If-Modified-Since' => $request->getModifiedSince()->format(\DateTime::RFC2822)
            ]
        ];

        return $this->guzzleClient->requestAsync('GET', $request->getUrl(), $options);
    }
}
