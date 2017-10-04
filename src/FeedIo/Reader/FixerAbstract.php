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
use Psr\Log\LoggerInterface;

abstract class FixerAbstract
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Psr\Log\LoggerInterface
     * @return $this
     */
    public function setLogger(LoggerInterface $logger) : FixerAbstract
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param FeedInterface $feed
     * @return FixerAbstract
     */
    abstract public function correct(FeedInterface $feed) : FixerAbstract;
}
