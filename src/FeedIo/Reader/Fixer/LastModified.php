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
use FeedIo\Reader\FixerAbstract;

class LastModified extends FixerAbstract
{

    /**
     * @param  FeedInterface $feed
     * @return $this
     */
    public function correct(FeedInterface $feed)
    {
        if (is_null($feed->getLastModified())) {
            $this->logger->notice("correct last modified date for feed {$feed->getTitle()}");
            $feed->setLastModified(
                        $this->searchLastModified($feed)
            );
        }

        return $this;
    }

    /**
     * @param  FeedInterface $feed
     * @return \DateTime
     */
    public function searchLastModified(FeedInterface $feed)
    {
        $latest = new \DateTime('@0');

        foreach ($feed as $item) {
            if ($item->getLastModified() > $latest) {
                $latest = $item->getLastModified();
            }
        }

        return $latest;
    }
}
