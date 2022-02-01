<?php

declare(strict_types=1);

namespace FeedIo\Factory\Builder;

use FeedIo\Factory\LoggerBuilderInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @package FeedIo
 */
class NullLoggerBuilder implements LoggerBuilderInterface
{
    public function __construct()
    {
    }

    public function getLogger(): LoggerInterface
    {
        return new NullLogger();
    }

    public function getMainClassName(): string
    {
        return '\Psr\Log\NullLogger';
    }

    public function getPackageName(): string
    {
        return 'psr/log';
    }
}
