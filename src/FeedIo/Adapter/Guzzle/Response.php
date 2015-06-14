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


use FeedIo\Adapter\ResponseInterface;
use \GuzzleHttp\Message\ResponseInterface as GuzzleResponseInterface;

/**
 * Guzzle dependent HTTP Response
 */
class Response implements ResponseInterface
{

    const HTTP_LAST_MODIFIED = 'Last-Modified';

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
     * @return \DateTime|null
     */
    public function getLastModified()
    {
        if ( $this->guzzleResponse->hasHeader(static::HTTP_LAST_MODIFIED) ) {
            return \DateTime::createFromFormat(\DateTime::RFC2822, $this->getHeader(static::HTTP_LAST_MODIFIED));
        }
        
        return null;
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
     * @return array|string
     */
    public function getHeader($name)
    {
        return $this->guzzleResponse->getHeader($name);
    }

}
