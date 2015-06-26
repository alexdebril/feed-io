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
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Guzzle dependent HTTP Response
 */
class Response implements ResponseInterface
{

    const HTTP_LAST_MODIFIED = 'Last-Modified';

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $psrResponse;

    /**
     * @param \Psr\Http\Message\ResponseInterface
     */
    public function __construct(PsrResponseInterface $psrResponse)
    {
        $this->psrResponse = $psrResponse;
    }

    /**
     * @return \Psr\Http\Message\StreamInterface
     */
    public function getBody()
    {
        return $this->psrResponse->getBody();
    }

    /**
     * @return \DateTime|null
     */
    public function getLastModified()
    {
        if ($this->psrResponse->hasHeader(static::HTTP_LAST_MODIFIED)) {
            $lastModified = \DateTime::createFromFormat(\DateTime::RFC2822, $this->getHeader(static::HTTP_LAST_MODIFIED));

            return false === $lastModified ? null : $lastModified;
        }

        return;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->psrResponse->getHeaders();
    }

    /**
     * @param  string       $name
     * @return string[]
     */
    public function getHeader($name)
    {
        return $this->psrResponse->getHeader($name);
    }
}
