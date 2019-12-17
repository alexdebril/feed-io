<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;

interface MediaHashInterface
{
    /**
     * @return string
     */
    public function getContent() : ?string;

    /**
     * @param  string $content
     * @return MediaHashInterface
     */
    public function setContent(?string $content) : MediaHashInterface;


    /**
     * @return int
     */
    public function getAlgo() : ?int;

    /**
     * @param  int $algo
     * @return MediaHashInterface
     */
    public function setAlgo(?int $algo) : MediaHashInterface;
}
