<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Filter;

use FeedIo\FeedInterface;
use FeedIo\Feed\ItemInterface;
use FeedIo\FilterInterface;

class ModifiedSince implements FilterInterface
{
    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @param FeedInterface $feed
     * @return $this
     */
    public function init(FeedInterface $feed)
    {
        $this->date = $feed->getLastModified();

        return $this;
    }

    /**
     * @param  ItemInterface $item
     * @return bool
     */
    public function isValid(ItemInterface $item)
    {
        if ($item->getLastModified() instanceof \DateTime) {
            return $item->getLastModified() > $this->date;
        }

        return false;
    }
}
