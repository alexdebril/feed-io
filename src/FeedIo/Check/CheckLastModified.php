<?php declare(strict_types=1);


namespace FeedIo\Check;

use FeedIo\Feed;
use FeedIo\FeedIo;

/**
 * Class CheckLastModified
 * @codeCoverageIgnore
 */
class CheckLastModified implements CheckInterface
{
    public function perform(FeedIo $feedIo, Feed $feed, Result $result): bool
    {
        $lastModifiedDates = [];
        $result->setModifiedSince($feed->getLastModified());
        /** @var \FeedIo\Feed\ItemInterface $item */
        foreach ($feed as $i => $item) {
            $lastModifiedDates[] = $item->getLastModified();
        }
        sort($lastModifiedDates);
        $first = current($lastModifiedDates);
        $last = end($lastModifiedDates);

        $result->setItemDates($lastModifiedDates);
        if (!($last > $first)) {
            $result->markAsFailed(Result::TEST_NORMAL_DATE_FLOW);
        }
        return true;
    }
}
