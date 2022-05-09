<?php

declare(strict_types=1);

namespace FeedIo\Filter;

use FeedIo\FeedInterface;

class Chain
{
    private array $filters;

    public function add(FilterInterface $filter): void
    {
        $this->filters[] = $filter;
    }

    public function filter(FeedInterface $feed): iterable
    {
        foreach ($feed as $item) {
            foreach ($this->filters as $filter) {
                if (!$filter->filter($item)) {
                    continue 2;
                }
            }

            yield $item;
        }
    }
}
