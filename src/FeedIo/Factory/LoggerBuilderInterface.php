<?php

declare(strict_types=1);

namespace FeedIo\Factory;

use Psr\Log\LoggerInterface;

/**
 * @package FeedIo
 */
interface LoggerBuilderInterface extends BuilderInterface
{
    /**
     * This method MUST return a valid PSR3 logger
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface;
}
