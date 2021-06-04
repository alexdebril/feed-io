<?php declare(strict_types=1);


namespace FeedIo\Check;

use FeedIo\FeedInterface;
use FeedIo\FeedIo;

/**
 * Interface CheckInterface
 * @codeCoverageIgnore
 */
interface CheckInterface
{
    public function perform(FeedIo $feedIo, FeedInterface $feed, Result $result): bool;
}
