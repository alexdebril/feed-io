<?php

declare(strict_types=1);

namespace FeedIo\Reader;

class FixerSet
{
    protected array $fixers = [];

    public function add(FixerAbstract $fixer): FixerSet
    {
        $this->fixers[] = $fixer;

        return $this;
    }

    public function correct(Result $result): FixerSet
    {
        foreach ($this->fixers as $fixer) {
            $fixer->correct($result);
        }

        return $this;
    }
}
