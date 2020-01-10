<?php declare(strict_types=1);
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
use FeedIo\Reader\Result;

class PublicId extends FixerAbstract
{

    /**
     * @param  Result $result
     * @return $this
     */
    public function correct(Result $result) : FixerAbstract
    {
        $feed = $result->getFeed();

        $this->fixNode($feed);
        $this->fixItems($feed);

        return $this;
    }

    /**
     * @param  NodeInterface $node
     */
    protected function fixNode(NodeInterface $node) : void
    {
        if (is_null($node->getPublicId())) {
            $this->logger->notice("correct public id for node {$node->getTitle()}");
            $node->setPublicId($node->getLink());
        }
    }

    /**
     * @param  FeedInterface $feed
     */
    protected function fixItems(FeedInterface $feed) : void
    {
        foreach ($feed as $item) {
            $this->fixNode($item);
        }
    }
}
