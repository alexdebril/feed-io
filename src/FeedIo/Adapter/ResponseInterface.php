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
 * Describes a HTTP Response as returned by an instance of ClientInterface
 *
 */
interface ResponseInterface
{

    /**
     * @return string
     */
    public function getBody() : ? string;

    /**
     * @return \DateTime
     */
    public function getLastModified() : ?\DateTime;

    /**
     * @return iterable
     */
    public function getHeaders() : iterable;

    /**
     * @param  string $name
     * @return iterable
     */
    public function getHeader(string $name): iterable;

    /**
     * @return boolean
     */
    public function isModified() : bool;
}
