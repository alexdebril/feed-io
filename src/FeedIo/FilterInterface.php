<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;

use FeedIo\Feed\ItemInterface;

interface FilterInterface
{

    /**
     * @param  ItemInterface $item
     * @return bool
     */
    public function isValid(ItemInterface $item);
}
