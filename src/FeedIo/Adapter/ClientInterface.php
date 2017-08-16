<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Adapter;

use FeedIo\Adapter\ResponseInterface;

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
