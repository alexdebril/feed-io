<?php declare(strict_types=1);
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
use Psr\Log\LoggerInterface;
use \Psr\Log\NullLogger;

/**
 * @package FeedIo
 */
class NullLoggerBuilder implements LoggerBuilderInterface
{

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // Ignore config as NullLogger does not accept any config
        // Done for FeedIo\Factory compatibility
    }

    /**
     * @inheritdoc
     */
    public function getLogger() : LoggerInterface
    {
        return new \Psr\Log\NullLogger;
    }
 
    /**
     * This method MUST return the name of the main class
     * @return string
     */
    public function getMainClassName() : string
    {
        return '\Psr\Log\NullLogger';
    }
    
    /**
     * This method MUST return the name of the package name
     * @return string
     */
    public function getPackageName() : string
    {
        return 'psr/log';
    }
}
