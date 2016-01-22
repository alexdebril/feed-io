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

use FeedIo\Factory\LoggerBuilderInterface;
use \Psr\Log\NullLogger;

/**
 * @package FeedIo
 */
class NullLoggerBuilder implements LoggerBuilderInterface
{

    /**
     * This method MUST return a valid PSR3 logger
     * @return \Psr\Log\NullLogger
     */
    public function getLogger()
    {
        return new \Psr\Log\NullLogger;
    }
 
    /**
     * This method MUST return the name of the main class
     * @return string
     */
    public function getMainClassName()
    {
        return '\Psr\Log\NullLogger';
    }
    
    /**
     * This method MUST return the name of the package name
     * @return string
     */
    public function getPackageName()
    {
        return 'psr/log';
    }
    
}
