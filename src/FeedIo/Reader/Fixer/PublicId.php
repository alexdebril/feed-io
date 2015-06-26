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

class PublicId extends FixerAbstract
{

    /**
     * @param  FeedInterface $feed
     * @return $this
     */
    public function correct(FeedInterface $feed)
    {
        if (is_null($feed->getPublicId())) {
            $this->logger->notice("correct public id for feed {$feed->getTitle()}");
            $feed->setPublicId($feed->getLink());
            $this->fixItems($feed);
        }

        return $this;
    }

    /**
     * @param  FeedInterface $feed
     * @return $this
     */
    protected function fixItems(FeedInterface $feed)
    {
        foreach ($feed as $item) {
            $item->setPublicId($item->getLink());
        }

        return $this;
    }
}
