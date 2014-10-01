<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser;


use FeedIo\Feed\NodeInterface;

interface RuleInterface
{
    /**
     * @return string
     */
    public function getNodeName();

    /**
     * @param NodeInterface $node
     * @param $value
     * @return $this
     */
    public function set(NodeInterface $node, $value);
} 