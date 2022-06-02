<?php declare(strict_types=1);

namespace FeedIo\Adapter;

use FeedIo\Adapter\ClientInterface as AdapterClientInterface;
use Psr\Http\Client\ClientInterface;

class ClientFactory
{
    public function create(ClientInterface $client): AdapterClientInterface
    {
        return new Client($client);
    }
}
