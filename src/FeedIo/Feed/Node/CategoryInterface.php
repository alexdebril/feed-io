<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Node;

/**
 * Describe a Category instance
 *
 */
interface CategoryInterface
{

    /**
     * @return null|string
     */
    public function getTerm() : ? string;

    /**
     * @param  string $term
     * @return CategoryInterface
     */
    public function setTerm(string $term = null) : CategoryInterface;

    /**
     * @return null|string
     */
    public function getScheme() : ? string;

    /**
     * @param  string $scheme
     * @return CategoryInterface
     */
    public function setScheme(string $scheme = null) : CategoryInterface;

    /**
     * @return null|string
     */
    public function getLabel() : ? string;

    /**
     * @param  string $label
     * @return CategoryInterface
     */
    public function setLabel(string $label = null) : CategoryInterface;
}
