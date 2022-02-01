<?php

declare(strict_types=1);

namespace FeedIo\Reader;

use FeedIo\FeedInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

abstract class FixerAbstract
{
    protected LoggerInterface $logger;

    public function setLogger(LoggerInterface $logger): FixerAbstract
    {
        $this->logger = $logger;

        return $this;
    }

    abstract public function correct(Result $result): FixerAbstract;
}
