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

interface MediaCommunityInterface
{
    /**
     * @return float
     */
    public function getStarRatingAverage() : ?float;

    /**
     * @param  float $starRatingAverage
     * @return MediaCommunityInterface
     */
    public function setStarRatingAverage(?float $starRatingAverage) : MediaCommunityInterface;


    /**
     * @return int
     */
    public function getStarRatingCount() : ?int;

    /**
     * @param  string $starRatingCount
     * @return MediaCommunityInterface
     */
    public function setStarRatingCount(?int $starRatingCount) : MediaCommunityInterface;


    /**
     * @return int
     */
    public function getStarRatingMin() : ?int;

    /**
     * @param  string $starRatingMin
     * @return MediaCommunityInterface
     */
    public function setStarRatingMin(?int $starRatingMin) : MediaCommunityInterface;


    /**
     * @return int
     */
    public function getStarRatingMax() : ?int;

    /**
     * @param  string $starRatingMax
     * @return MediaCommunityInterface
     */
    public function setStarRatingMax(?int $starRatingMax) : MediaCommunityInterface;


    /**
     * @return int
     */
    public function getNbViews() : ?int;

    /**
     * @param  string $nbViews
     * @return MediaCommunityInterface
     */
    public function setNbViews(?int $nbViews) : MediaCommunityInterface;


    /**
     * @return int
     */
    public function getNbFavorites() : ?int;

    /**
     * @param  string $nbFavorites
     * @return MediaCommunityInterface
     */
    public function setNbFavorites(?int $nbFavorites) : MediaCommunityInterface;


    /**
     * @return array
     */
    public function getTags() : array;

    /**
     * @param  string $tags
     * @return MediaCommunityInterface
     */
    public function setTags(array $tags) : MediaCommunityInterface;
}
