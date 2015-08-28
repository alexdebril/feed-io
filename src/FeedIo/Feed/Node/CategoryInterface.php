<?php
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
     * @return string
     */
    public function getTerm();
    
    /**
     * @param  string $term
     * @return $this
     */
    public function setTerm($term);
    
    /**
     * @return string
     */
    public function getScheme();
    
    /**
     * @param  string $scheme
     * @return $this
     */
    public function setScheme($scheme);
    
    /**
     * @return string
     */
    public function getLabel();
    
    /**
     * @param  string $label
     * @return $this
     */
    public function setLabel($label);
    
}
