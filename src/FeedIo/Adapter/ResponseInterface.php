<?php
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
    public function getBody();

    /**
     * @return \DateTime
     */
    public function getLastModified();

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @param  string $name
     * @return string
     */
    public function getHeader($name);
}
