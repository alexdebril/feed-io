<?php

declare(strict_types=1);

namespace FeedIo\Factory;

use FeedIo\Adapter\ClientInterface;

/**
 * @package FeedIo
 */
interface ClientBuilderInterface extends BuilderInterface
{
    /**
     * This method MUST return a \FeedIo\Adapter\ClientInterface instance
     * @return \FeedIo\Adapter\ClientInterface
     */
    public function getClient(): ClientInterface;
}
