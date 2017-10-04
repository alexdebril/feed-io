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

/**
 * Null HTTP Response
 */
class NullResponse implements ResponseInterface
{

    /**
     * @return string
     */
    public function getBody() : ? string
    {
        return null;
    }

    /**
    * @return boolean
    */
    public function isModified() : bool
    {
        return true;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified() : ?\DateTime
    {
        return new \DateTime('@0');
    }

    /**
     * @return iterable
     */
    public function getHeaders() : iterable
    {
        return [];
    }

    /**
     * @param  string       $name
     * @return iterable
     */
    public function getHeader(string $name) : iterable
    {
        return [];
    }
}
