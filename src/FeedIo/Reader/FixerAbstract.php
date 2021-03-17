<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader;

use FeedIo\FeedInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

abstract class FixerAbstract
{
    protected LoggerInterface $logger;

    public function setLogger(LoggerInterface $logger) : FixerAbstract
    {
        $this->logger = $logger;

        return $this;
    }

    abstract public function correct(Result $result) : FixerAbstract;
}
