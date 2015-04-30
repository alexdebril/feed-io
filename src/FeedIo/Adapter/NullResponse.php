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
 * Null HTTP Response
 */
class NullResponse implements ResponseInterface
{

    /**
     * @return string
     */
    public function getBody()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return array();
    }

    /**
     * @param string $name
     * @return array|string
     */
    public function getHeader($name)
    {
        return $name;
    }

}
