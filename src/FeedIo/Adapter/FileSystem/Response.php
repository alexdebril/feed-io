<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Adapter\FileSystem;

use FeedIo\Adapter\ResponseInterface;

/**
 *
 */
class Response implements ResponseInterface
{

    /**
     * @var string
     */
    protected $fileContent;

    /**
     * @var \DateTime
     */
    protected $lastModified;

    /**
     * @param string    $fileContent
     * @param \DateTime $lastModified
     */
    public function __construct($fileContent, \DateTime $lastModified)
    {
        $this->fileContent  = $fileContent;
        $this->lastModified = $lastModified;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->fileContent;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return array();
    }

    /**
     * @param  string $name
     * @return string
     */
    public function getHeader($name)
    {
        return '';
    }

    /**
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }
}
