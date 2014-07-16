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

use FeedIo\Feed\NodeInterface;
use FeedIo\Feed\ItemInterface;

/**
 * Interface FeedInterface
 * Represents the top node of a news feed
 * @package FeedIo
 */
interface FeedInterface extends \Iterator, NodeInterface
{

    /**
     * Atom : feed.entry <feed><entry>
     * Rss  : rss.channel.item <rss><channel><item>
     * @param ItemInterface $item
     */
    public function add(ItemInterface $item);

}