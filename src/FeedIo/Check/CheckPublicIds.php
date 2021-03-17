<?php declare(strict_types=1);


namespace FeedIo\Check;

use FeedIo\Feed\ItemInterface;
use FeedIo\FeedInterface;
use FeedIo\FeedIo;

/**
 * Class CheckPublicIds
 * @codeCoverageIgnore
 */
class CheckPublicIds implements CheckInterface
{
    public function perform(FeedIo $feedIo, FeedInterface $feed, Result $result): bool
    {
        $publicIds = [];
        /** @var ItemInterface $item */
        foreach ($feed as $i => $item) {
            $publicIds[] = $item->getPublicId();
        }

        if (!$this->checkPublicIds($publicIds)) {
            $result->markAsFailed(Result::TEST_UNIQUE_IDS);
        }
        return true;
    }

    private function checkPublicIds(array $publicIds): bool
    {
        $deduplicated = array_unique($publicIds);
        return count($deduplicated) == count($publicIds);
    }
}
