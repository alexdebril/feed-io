<?php declare(strict_types=1);


namespace FeedIo\Check;

use FeedIo\Feed;
use FeedIo\FeedIo;

/**
 * Class CheckReadSince
 * @codeCoverageIgnore
 */
class CheckReadSince implements CheckInterface
{
    public function perform(FeedIo $feedIo, Feed $feed, Result $result): bool
    {
        try {
            $dates = $result->getItemDates();
            $count = count($dates);
            $last = $dates[$count - 1];
            if ($last != $dates[0]) {
                $pick = intval($count / 2);
                $lastModified = $dates[$pick];
            } else {
                $lastModified = $last->sub(new \DateInterval('P1D'));
            }
            $secondFeed = $feedIo->readSince($feed->getUrl(), $lastModified)->getFeed();
            if (0 === count($secondFeed)) {
                $result->setNotUpdateable();
                return false;
            }

            return true;
        } catch (\Throwable $exception) {
            $result->setNotUpdateable();
            return false;
        }
    }
}
