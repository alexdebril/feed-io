<?php
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

interface FixerInterface
{

    /**
     * @param Psr\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);
    
    /**
     * @param FeedIo\FeedInterface $feed
     */
    public function correct(FeedInterface $feed);

}
