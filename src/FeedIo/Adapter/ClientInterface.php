<?php declare(strict_types=1);

namespace FeedIo\Adapter;

/**
 * Describes a HTTP Client used by \FeedIo\Reader
 *
 * getResponse() MUST return an instance of \FeedIo\Adapter\ResponseInterface or throw an exception
 *
 */
interface ClientInterface
{

    /**
     * @param  string                               $url
     * @param  \DateTime                            $modifiedSince
     * @throws \FeedIo\Adapter\NotFoundException
     * @throws \FeedIo\Adapter\ServerErrorException
     * @return \FeedIo\Adapter\ResponseInterface
     */
    public function getResponse(string $url, \DateTime $modifiedSince) : ResponseInterface;
}
