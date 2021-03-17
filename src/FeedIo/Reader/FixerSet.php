<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader;

class FixerSet
{
    protected array $fixers = [];

    public function add(FixerAbstract $fixer) : FixerSet
    {
        $this->fixers[] = $fixer;

        return $this;
    }

    public function correct(Result $result) : FixerSet
    {
        foreach ($this->fixers as $fixer) {
            $fixer->correct($result);
        }

        return $this;
    }
}
