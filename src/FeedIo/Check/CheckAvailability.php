<?php declare(strict_types=1);


namespace FeedIo\Check;

use FeedIo\Feed;
use FeedIo\FeedIo;

/**
 * Class CheckAvailability
 * @codeCoverageIgnore
 */
class CheckAvailability implements CheckInterface
{
    public function perform(FeedIo $feedIo, Feed $feed, Result $result): bool
    {
        try {
            $feedIo->read($feed->getUrl(), $feed);
            $count = count($feed);
            $result->setItemCount($count);
            if (0 === $count) {
                $result->setNotUpdateable();
                return false;
            }
            return true;
        } catch (\Throwable $exception) {
            $result->setNotUpdateable();
            $result->setNotAccessible();
            return false;
        }
    }
}
