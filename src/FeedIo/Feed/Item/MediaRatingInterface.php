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

interface MediaRatingInterface
{
    /**
     * @return string
     */
    public function getContent() : ?string;

    /**
     * @param  string $rating
     * @return MediaRatingInterface
     */
    public function setContent(?string $rating) : MediaRatingInterface;


    /**
     * @return string
     */
    public function getScheme() : ?string;

    /**
     * @param  string $scheme
     * @return MediaRatingInterface
     */
    public function setScheme(?string $scheme) : MediaRatingInterface;
}
