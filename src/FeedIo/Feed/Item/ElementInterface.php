<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;


interface ElementInterface
{

    /**
     * @return string
     */
    public function getName();
    
    /**
     * @param string $name
     * @return $this
     */   
    public function setName($name);
    
    /**
     * @return string
     */
    public function getValue();
    
    /**
     * @param string $value
     * @return $this
     */   
    public function setValue($value);
    
    /**
     * @param string $name
     * @return string
     */
    public function getAttribute($name);
    
    /**
     * @return array
     */
    public function getAttributes();
    
    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setAttribute($name, $value);
    
}
