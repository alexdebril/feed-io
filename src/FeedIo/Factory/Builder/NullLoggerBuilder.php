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
    public function __construct()
    {
    }

    public function getLogger() : LoggerInterface
    {
        return new NullLogger;
    }
 
    public function getMainClassName() : string
    {
        return '\Psr\Log\NullLogger';
    }
    
    public function getPackageName() : string
    {
        return 'psr/log';
    }
}
