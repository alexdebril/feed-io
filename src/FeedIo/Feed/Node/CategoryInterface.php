<?php declare(strict_types=1);

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
