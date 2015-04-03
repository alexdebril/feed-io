<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader\Fixer;

use FeedIo\FeedInterface;
use FeedIo\Reader\FixerInterface;
use Psr\Log\LoggerInterface;

class LastModified implements FixerInterface
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;   

    /**
     * @param \Psr\Log\LoggerInterface
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        
        return $this;
    }

    public function correct(FeedInterface $feed)
    {
        if ( is_null($feed->getLastModified()) ) {
            $feed->setLastModified(
                        $this->searchLastModified($feed)
            );
        }
        
        return $this;
    }

    public function searchLastModified(FeedInterface $feed)
    {
        $latest = new \DateTime('@0');
        
        foreach ( $feed as $item ) {
            if ( $item->getLastModified() > $latest ) {
                $latest = $item->getLastModified();
            }
        }
        
        return $latest;
    }

}
