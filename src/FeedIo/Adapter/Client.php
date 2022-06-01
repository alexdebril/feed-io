<?php

declare(strict_types=1);

namespace FeedIo\Adapter;

use DateTime;
use Nyholm\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;

class Client implements ClientInterface
{
    public const DEFAULT_USER_AGENT = 'Mozilla/5.0 (X11; U; Linux i686; fr; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1';

    public function __construct(private PsrClientInterface $client, private string $userAgent = self::DEFAULT_USER_AGENT)
    {
    }

    /**
     * @param string $userAgent The new user-agent
     * @return self
     */
    public function setUserAgent(string $userAgent): Client
    {
        $this->userAgent = $userAgent;

        return $this;
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
        $headers = $this->getHeaders($modifiedSince);
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

    /**
     * @param DateTime|null $modifiedSince
     * @return array
     */
    protected function getHeaders(DateTime $modifiedSince = null): array
    {
        $headers = [
            'Accept-Encoding' => 'gzip, deflate',
            'User-Agent' => $this->userAgent,
        ];

        if ($modifiedSince) {
            $headers['If-Modified-Since'] = $modifiedSince->format(\DateTime::RFC2822);
        }

        return $headers;
    }
}
