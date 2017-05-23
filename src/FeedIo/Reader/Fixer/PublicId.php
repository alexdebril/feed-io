<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader\Fixer;

use FeedIo\FeedInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\Reader\FixerAbstract;

class PublicId extends FixerAbstract
{

    /**
     * @param  FeedInterface $feed
     * @return $this
     */
    public function correct(FeedInterface $feed)
    {
        $this->fixNode($feed);

        $this->fixItems($feed);

        return $this;
    }

    /**
     * @param  NodeInterface $node
     * @return $this
     */
    protected function fixNode(NodeInterface $node)
    {
        if (is_null($node->getPublicId())) {
            $this->logger->notice("correct public id for node {$node->getTitle()}");
            $node->setPublicId($node->getLink());
        }
    }

    /**
     * @param  FeedInterface $feed
     * @return $this
     */
    protected function fixItems(FeedInterface $feed)
    {
        foreach ($feed as $item) {
            $this->fixNode($item);
        }

        return $this;
    }

}
