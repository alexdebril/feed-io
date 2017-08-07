<?php declare(strict_types=1);
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
     * @return boolean
     */
    public function isModified() : bool
    {
        return $this->psrResponse->getStatusCode() != 304 && $this->psrResponse->getBody()->getSize() > 0;
    }

    /**
     * @return string
     */
    public function getBody() : ? string
    {
        return $this->psrResponse->getBody()->getContents();
    }

    /**
     * @return \DateTime|null
     */
    public function getLastModified() : ?\DateTime
    {
        if ($this->psrResponse->hasHeader(static::HTTP_LAST_MODIFIED)) {
            $lastModified = \DateTime::createFromFormat(\DateTime::RFC2822, $this->getHeader(static::HTTP_LAST_MODIFIED)[0]);

            return false === $lastModified ? null : $lastModified;
        }

        return null;
    }

    /**
     * @return iterable
     */
    public function getHeaders()  : iterable
    {
        return $this->psrResponse->getHeaders();
    }

    /**
     * @param  string       $name
     * @return iterable
     */
    public function getHeader(string $name) : iterable
    {
        return $this->psrResponse->getHeader($name);
    }
}
