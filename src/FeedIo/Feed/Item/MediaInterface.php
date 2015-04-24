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


interface MediaInterface
{

    /**
     * @return string
     */
    public function getType();
    
    /**
     * @param string $type
     * @return $this
     */   
    public function setType($type);
    
    /**
     * @return string
     */
    public function getUrl();
    
    /**
     * @param string $url
     * @return $this
     */   
    public function setUrl($url);
    
    /**
     * @return string
     */
    public function getLenght();
    
    /**
     * @param string $lenght
     * @return $this
     */   
    public function setLenght($lenght);
    
}
