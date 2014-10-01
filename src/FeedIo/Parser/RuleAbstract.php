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

abstract class RuleAbstract
{
    const NODE_NAME = 'node';

    /**
     * @return string
     */
    public function getNodeName()
    {
        return static::NODE_NAME;
    }

    /**
     * @param NodeInterface $node
     * @param $value
     * @return $this
     */
    abstract public function set(NodeInterface $node, $value);
} 