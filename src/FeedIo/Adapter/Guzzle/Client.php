<?php
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
use FeedIo\Adapter\NotFoundException;
use FeedIo\Adapter\ServerErrorException;
use GuzzleHttp\Exception\BadResponseException;

/**
 * Guzzle dependent HTTP client
 */
class Client implements ClientInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $guzzleClient;

    /**
     * @param \GuzzleHttp\ClientInterface $guzzleClient
     */
    public function __construct(\GuzzleHttp\ClientInterface $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param  string                               $url
     * @param  \DateTime                            $modifiedSince
     * @throws \FeedIo\Adapter\NotFoundException
     * @throws \FeedIo\Adapter\ServerErrorException
     * @return \FeedIo\Adapter\ResponseInterface
     */
    public function getResponse($url, \DateTime $modifiedSince)
    {
        try {
            $options = [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (X11; U; Linux i686; fr; rv:1.9.1.1) Gecko/20090715 Firefox/3.5.1',
                    'If-Modified-Since' => $modifiedSince->format(\DateTime::RFC2822)
                ]
            ];

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
}
