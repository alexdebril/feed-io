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

use FeedIo\FeedInterface;

class FixerSet
{
    protected $fixers = array();

    /**
     * @param \FeedIo\Reader\FixerAbstract
     * @return FixerSet
     */
    public function add(FixerAbstract $fixer) : FixerSet
    {
        $this->fixers[] = $fixer;

        return $this;
    }

    /**
     * @param  FeedInterface $feed
     * @return FixerSet
     */
    public function correct(FeedInterface $feed) : FixerSet
    {
        foreach ($this->fixers as $fixer) {
            $fixer->correct($feed);
        }

        return $this;
    }
}
