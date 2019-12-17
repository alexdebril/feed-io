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

interface MediaCategoryInterface
{
    /**
     * @return string
     */
    public function getText() : ?string;

    /**
     * @param  string $category
     * @return MediaCategoryInterface
     */
    public function setText(?string $category) : MediaCategoryInterface;


    /**
     * @return string
     */
    public function getLabel() : ?string;

    /**
     * @param  string $label
     * @return MediaCategoryInterface
     */
    public function setLabel(?string $label) : MediaCategoryInterface;


    /**
     * @return string
     */
    public function getScheme() : ?string;

    /**
     * @param  string $scheme
     * @return MediaCategoryInterface
     */
    public function setScheme(?string $scheme) : MediaCategoryInterface;
}
