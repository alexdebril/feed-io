<?php

declare(strict_types=1);

namespace FeedIo\Adapter\Http;

use DateTime;
use FeedIo\Adapter\ClientInterface;
use FeedIo\Adapter\NotFoundException;
use FeedIo\Adapter\ResponseInterface;
use FeedIo\Adapter\ServerErrorException;
use Nyholm\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;

class Client implements ClientInterface
{
    public function __construct(private readonly PsrClientInterface $client)
    {
    }

    /**
     * @param string $url
     * @param DateTime|null $modifiedSince
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function getResponse(string $url, DateTime $modifiedSince = null): ResponseInterface
    {
        if ($modifiedSince) {
            $headResponse = $this->request('HEAD', $url, $modifiedSince);
            if (304 === $headResponse->getStatusCode()) {
                return $headResponse;
            }
        }

        return $this->request('GET', $url, $modifiedSince);
    }

    /**
     * @param string $method
     * @param string $url
     * @param DateTime|null $modifiedSince
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    protected function request(string $method, string $url, DateTime $modifiedSince = null): ResponseInterface
    {
        $headers = [];

        if ($modifiedSince) {
            $headers['If-Modified-Since'] = $modifiedSince->format(DateTime::RFC2822);
        }

        $request = new Request($method, $url, $headers);

        $timeStart = microtime(true);
        $psrResponse = $this->client->sendRequest($request);
        $duration = microtime(true) - $timeStart;

        switch ($psrResponse->getStatusCode()) {
            case 200:
            case 304:
                return new Response($psrResponse, $duration);
            case 404:
                throw new NotFoundException('not found', $duration);
            default:
                throw new ServerErrorException($psrResponse, $duration);
        }
    }
}
