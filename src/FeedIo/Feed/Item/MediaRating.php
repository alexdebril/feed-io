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

class MediaRating implements MediaRatingInterface
{
    /**
     * @var string
     */
    protected $content;


    /**
     * @var string
     */
    protected $scheme;


    /**
     * @return string
     */
    public function getContent() : ?string
    {
        return $this->content;
    }

    /**
     * @param  string $content
     * @return MediaRatingInterface
     */
    public function setContent(?string $content) : MediaRatingInterface
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getScheme() : ?string
    {
        return $this->scheme;
    }

    /**
     * @param  string $scheme
     * @return MediaRatingInterface
     */
    public function setScheme(?string $scheme) : MediaRatingInterface
    {
        $this->scheme = $scheme;

        return $this;
    }
}
