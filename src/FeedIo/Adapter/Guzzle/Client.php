<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Adapter\Guzzle;

use FeedIo\Adapter\ClientInterface;
use FeedIo\Adapter\Guzzle\Async\ReaderInterface;
use FeedIo\Adapter\NotFoundException;
use FeedIo\Adapter\ResponseInterface;
use FeedIo\Adapter\ServerErrorException;
use FeedIo\Async\Request;
use \GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Exception\BadResponseException;

/**
 * Guzzle dependent HTTP client
 */
class Client implements ClientInterface
{

    /**
     * Default user agent provided with the package
     */
    const DEFAULT_USER_AGENT = 'Mozilla/5.0 (X11; U; Linux i686; fr; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1';

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $guzzleClient;

    /**
     * @var string
     */
    protected $userAgent;

    /**
     * @param \GuzzleHttp\ClientInterface $guzzleClient
     * @param string $userAgent
     */
    public function __construct(\GuzzleHttp\ClientInterface $guzzleClient, string $userAgent = self::DEFAULT_USER_AGENT)
    {
        $this->guzzleClient = $guzzleClient;
        $this->userAgent = $userAgent;
    }

    /**
     * @param  string $userAgent The new user-agent
     * @return Client
     */
    public function setUserAgent(string $userAgent) : Client
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @param string $url
     * @param \DateTime $modifiedSince
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getResponse(string $url, \DateTime $modifiedSince) : ResponseInterface
    {
        try {
            $options = $this->getOptions($modifiedSince);

            return new Response($this->guzzleClient->request('get', $url, $options));
        } catch (BadResponseException $e) {
            switch ((int) $e->getResponse()->getStatusCode()) {
                case 404:
                    throw new NotFoundException($e->getMessage());
                default:
                    throw new ServerErrorException($e->getMessage());
            }
        }
    }

    /**
     * @param iterable $requests
     * @param ReaderInterface $reader
     * @return \Generator
     */
    public function getPromises(iterable $requests, ReaderInterface $reader) : \Generator
    {
        foreach ($requests as $request) {
            yield $this->getPromise($request, $reader);
        }
    }

    /**
     * @param Request $request
     * @param ReaderInterface $reader
     * @return PromiseInterface
     */
    protected function getPromise(Request $request, ReaderInterface $reader) : PromiseInterface
    {
        $promise = $this->newPromise($request);

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
        $options = $this->getOptions($request->getModifiedSince());

        return $this->guzzleClient->requestAsync('GET', $request->getUrl(), $options);
    }

    /**
     * @param \DateTime $modifiedSince
     * @return array
     */
    protected function getOptions(\DateTime $modifiedSince) : array
    {
        return [
            'headers' => [
                'User-Agent' => $this->userAgent,
                'If-Modified-Since' => $modifiedSince->format(\DateTime::RFC2822)
            ]
        ];
    }
}
