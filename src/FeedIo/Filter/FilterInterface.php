<?php

declare(strict_types=1);

namespace FeedIo\Filter;

use FeedIo\Feed\ItemInterface;

interface FilterInterface
{
    /**
     * Returns `true` if the item is to be returned.
     *
     * @param ItemInterface $item
     * @return bool
     */
    public function filter(ItemInterface $item): bool;
}
