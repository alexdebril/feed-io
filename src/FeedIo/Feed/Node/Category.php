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
     * @return string
     */
    public function getTerm()
    {
        return $this->term;
    }
    
    /**
     * @param  string $term
     * @return $this
     */
    public function setTerm($term)
    {
        $this->term = $term;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getScheme()    
    {
        return $this->scheme;
    }
    
    /**
     * @param  string $scheme
     * @return $this
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLabel()    
    {
        return $this->label;
    }
    
    /**
     * @param  string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;
        
        return $this;
    }
    
}
