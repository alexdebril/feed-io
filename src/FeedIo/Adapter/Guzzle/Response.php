<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25/01/15
 * Time: 13:53
 */

namespace FeedIo\Adapter\Guzzle;


use FeedIo\Adapter\ResponseInterface;
use \GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

class Response implements ResponseInterface
{
    /**
     * @var \GuzzleHttp\Message\ResponseInterface
     */
    protected $guzzleResponse;

    /**
     * @param GuzzleResponseInterface $guzzleResponse
     */
    public function __construct(GuzzleResponseInterface $guzzleResponse)
    {
        $this->guzzleResponse = $guzzleResponse;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->guzzleResponse->getBody();
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->guzzleResponse->getHeaders();
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeader($name)
    {
        return $this->guzzleResponse->getHeader($name);
    }

}