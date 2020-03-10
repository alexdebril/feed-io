<?php declare(strict_types=1);
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
use FeedIo\Reader\Result;

class LastModifiedSince extends FixerAbstract
{

    /**
     * @param Result $result
     * @return FixerAbstract
     * @throws \Exception
     *
     */
    public function correct(Result $result) : FixerAbstract
    {
        $feed = $result->getFeed();
        $date = new \DateTime('@0');

        if (count($feed) === 0 && (is_null($feed->getLastModified()) || $feed->getLastModified() == $date)) {
            $this->logger->notice("set last modified date to modifiedSince arg for feed {$feed->getTitle()}");
            $feed->setLastModified($result->getModifiedSince());
        }

        return $this;
    }
}
