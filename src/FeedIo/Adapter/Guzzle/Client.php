<?php

declare(strict_types=1);

namespace FeedIo\Adapter\Guzzle;

use DateTime;
use FeedIo\Adapter\ClientInterface;
use FeedIo\Adapter\NotFoundException;
use FeedIo\Adapter\ResponseInterface;
use FeedIo\Adapter\ServerErrorException;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\TransferStats;

/**
 * Guzzle dependent HTTP client
 */
class Client implements ClientInterface
{
    /**
     * Default user agent provided with the package
     */
    public const DEFAULT_USER_AGENT = 'Mozilla/5.0 (X11; U; Linux i686; fr; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1';

    public function __construct(
        protected GuzzleClientInterface $guzzleClient,
        protected string $userAgent = self::DEFAULT_USER_AGENT
    ) {
    }

    /**
     * @param  string $userAgent The new user-agent
     * @return Client
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getResponse(string $url, DateTime $modifiedSince = null): ResponseInterface
    {
        if ($modifiedSince) {
            $headResponse = $this->request('head', $url, $modifiedSince);
            if (304 === $headResponse->getStatusCode()) {
                return $headResponse;
            }
        }

        return $this->request('get', $url, $modifiedSince);
    }

    /**
     * @param string $method
     * @param string $url
     * @param DateTime|null $modifiedSince
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request(string $method, string $url, DateTime $modifiedSince = null): ResponseInterface
    {
        $options = $this->getOptions($modifiedSince);
        $duration = 0;
        $options['on_stats'] = function (TransferStats $stats) use (&$duration) {
            $duration = $stats->getTransferTime();
        };
        $psrResponse = $this->guzzleClient->request($method, $url, $options);
        switch ((int) $psrResponse->getStatusCode()) {
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
    protected function getOptions(DateTime $modifiedSince = null): array
    {
        $headers = [
            'Accept-Encoding' => 'gzip, deflate',
            'User-Agent' => $this->userAgent,
        ];
        if ($modifiedSince) {
            $headers['If-Modified-Since'] = $modifiedSince->format(\DateTime::RFC2822);
        }
        return [
            'http_errors' => false,
            'timeout' => 30,
            'headers' => $headers
        ];
    }
}
