<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;

use GuzzleHttp\Client;

use Psr\Log\LoggerInterface;

class Reader
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param Client $client
     * @param LoggerInterface $logger
     */
    function __construct(Client $client)
    {
        $this->client = $client;
    }

} 