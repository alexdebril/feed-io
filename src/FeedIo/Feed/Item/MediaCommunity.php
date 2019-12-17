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

class MediaCommunity implements MediaCommunityInterface
{
    /**
     * @var float
     */
    protected $starRatingAverage;

    /**
     * @var int
     */
    protected $starRatingCount;

    /**
     * @var int
     */
    protected $starRatingMin;

    /**
     * @var int
     */
    protected $starRatingMax;

    /**
     * @var int
     */
    protected $nbViews;

    /**
     * @var int
     */
    protected $nbFavorites;

    /**
     * @var array
     */
    protected $tags = array();

    /**
     * @return float
     */
    public function getStarRatingAverage() : ?float
    {
        return $this->starRatingAverage;
    }

    /**
     * @param  float $starRatingAverage
     * @return MediaCommunityInterface
     */
    public function setStarRatingAverage(?float $starRatingAverage) : MediaCommunityInterface
    {
        $this->starRatingAverage = $starRatingAverage;

        return $this;
    }

    /**
     * @return int
     */
    public function getStarRatingCount() : ?int
    {
        return $this->starRatingCount;
    }

    /**
     * @param  int $starRatingCount
     * @return MediaCommunityInterface
     */
    public function setStarRatingCount(?int $starRatingCount) : MediaCommunityInterface
    {
        $this->starRatingCount = $starRatingCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getStarRatingMin() : ?int
    {
        return $this->starRatingMin;
    }

    /**
     * @param  int $starRatingMin
     * @return MediaCommunityInterface
     */
    public function setStarRatingMin(?int $starRatingMin) : MediaCommunityInterface
    {
        $this->starRatingMin = $starRatingMin;

        return $this;
    }

    /**
     * @return int
     */
    public function getStarRatingMax() : ?int
    {
        return $this->starRatingMax;
    }

    /**
     * @param  int $starRatingMax
     * @return MediaCommunityInterface
     */
    public function setStarRatingMax(?int $starRatingMax) : MediaCommunityInterface
    {
        $this->starRatingMax = $starRatingMax;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbViews() : ?int
    {
        return $this->nbViews;
    }

    /**
     * @param  int $nbViews
     * @return MediaCommunityInterface
     */
    public function setNbViews(?int $nbViews) : MediaCommunityInterface
    {
        $this->nbViews = $nbViews;

        return $this;
    }

    /**
     * @return int
     */
    public function getNbFavorites() : ?int
    {
        return $this->nbFavorites;
    }

    /**
     * @param  int $nbFavorites
     * @return MediaCommunityInterface
     */
    public function setNbFavorites(?int $nbFavorites) : MediaCommunityInterface
    {
        $this->nbFavorites = $nbFavorites;

        return $this;
    }

    /**
     * @return array
     */
    public function getTags() : array
    {
        return $this->tags;
    }

    /**
     * @param  array $tags
     * @return MediaCommunityInterface
     */
    public function setTags(array $tags) : MediaCommunityInterface
    {
        $this->tags = $tags;

        return $this;
    }
}
