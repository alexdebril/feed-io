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

class Category implements CategoryInterface
{

    /**
     * @var string
     */
    protected $term;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $label;

    /**
     * @return null|string
     */
    public function getTerm() : ? string
    {
        return $this->term;
    }

    /**
     * @param  string $term
     * @return CategoryInterface
     */
    public function setTerm(string $term = null) : CategoryInterface
    {
        $this->term = $term;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getScheme() : ? string
    {
        return $this->scheme;
    }

    /**
     * @param  string $scheme
     * @return CategoryInterface
     */
    public function setScheme(string $scheme = null) : CategoryInterface
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLabel() : ? string
    {
        return $this->label;
    }

    /**
     * @param  string $label
     * @return CategoryInterface
     */
    public function setLabel(string $label = null) : CategoryInterface
    {
        $this->label = $label;

        return $this;
    }
}
