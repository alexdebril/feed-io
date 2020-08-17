<?php declare(strict_types=1);


namespace FeedIo\Check;

use FeedIo\Feed;
use FeedIo\FeedIo;

/**
 * Interface CheckInterface
 * @codeCoverageIgnore
 */
interface CheckInterface
{

    /**
     * Performs the check and return false if the full process must stop
     *
     * @param FeedIo $feedIo
     * @param Feed $feed
     * @param Result $result
     * @return bool
     */
    public function perform(FeedIo $feedIo, Feed $feed, Result $result): bool;
}
