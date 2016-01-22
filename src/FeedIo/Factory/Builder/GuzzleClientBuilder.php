<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Factory\Builder;

use FeedIo\Factory\ClientBuilderInterface;
use \FeedIo\Adapter\Guzzle\Client;
use \GuzzleHttp\Client as GuzzleClient;

/**
 * @package FeedIo
 */
class GuzzleClientBuilder implements ClientBuilderInterface
{

    /**
     * This method MUST return a \FeedIo\Adapter\ClientInterface instance
     * @return \FeedIo\Adapter\ClientInterface
     */
    public function getClient()
    {
        return new Client(new GuzzleClient);
    }
 
    /**
     * This method MUST return the name of the main class
     * @return string
     */
    public function getMainClassName()
    {
        return '\GuzzleHttp\Client';
    }
    
    /**
     * This method MUST return the name of the package name
     * @return string
     */
    public function getPackageName()
    {
        return 'guzzlehttp/guzzle';
    }
    
}
