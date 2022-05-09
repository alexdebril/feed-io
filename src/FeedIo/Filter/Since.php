<?php

declare(strict_types=1);

namespace FeedIo\Filter;

use DateTime;
use FeedIo\Feed\ItemInterface;

class Since implements FilterInterface
{
    private DateTime $date;

    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    public function filter(ItemInterface $item): bool
    {
        return $item->getLastModified() >= $this->date;
    }
}
