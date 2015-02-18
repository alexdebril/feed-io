<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25/01/15
 * Time: 13:37
 */

namespace FeedIo\Adapter\Guzzle;


use FeedIo\Adapter\ClientInterface;
use FeedIo\Adapter\NotFoundException;
use FeedIo\Adapter\ServerErrorException;
use GuzzleHttp\Exception\BadResponseException;

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
     * @param string $url
     * @param \DateTime $modifiedSince
     * @throws \FeedIo\Adapter\NotFoundException
     * @throws \FeedIo\Adapter\ServerErrorException
     * @return \FeedIo\Adapter\ResponseInterface
     */
    public function getResponse($url, \DateTime $modifiedSince)
    {
        try {
            return new Response($this->guzzleClient->get($url));
        } catch (BadResponseException $e) {
            switch( (int) $e->getResponse()->getStatusCode() ) {
                case 404:
                    throw new NotFoundException($e->getMessage());
                default:
                    throw new ServerErrorException($e->getMessage());
            }
        }
    }


}