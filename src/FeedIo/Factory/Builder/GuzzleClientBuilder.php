<?php

declare(strict_types=1);

namespace FeedIo\Factory\Builder;

use FeedIo\Adapter\ClientInterface;
use FeedIo\Factory\ClientBuilderInterface;
use FeedIo\Adapter\Guzzle\Client;
use GuzzleHttp\Client as GuzzleClient;

/**
 * @package FeedIo
 */
class GuzzleClientBuilder implements ClientBuilderInterface
{
    public function __construct(
        private array $config = []
    ) {
    }

    /**
     * This method MUST return a \FeedIo\Adapter\ClientInterface instance
     * @return \FeedIo\Adapter\ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return new Client(new GuzzleClient($this->config));
    }

    /**
     * This method MUST return the name of the main class
     * @return string
     */
    public function getMainClassName(): string
    {
        return '\GuzzleHttp\Client';
    }

    /**
     * This method MUST return the name of the package name
     * @return string
     */
    public function getPackageName(): string
    {
        return 'guzzlehttp/guzzle';
    }
}
