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

class MediaCategory implements MediaCategoryInterface
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @return string
     */
    public function getText() : ?string
    {
        return $this->text;
    }

    /**
     * @param  string $text
     * @return MediaCategoryInterface
     */
    public function setText(?string $text) : MediaCategoryInterface
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel() : ?string
    {
        return $this->label;
    }

    /**
     * @param  string $label
     * @return MediaCategoryInterface
     */
    public function setLabel(?string $label) : MediaCategoryInterface
    {
        $this->label = $label;

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
     * @return MediaCategoryInterface
     */
    public function setScheme(?string $scheme) : MediaCategoryInterface
    {
        $this->scheme = $scheme;

        return $this;
    }
}
