<?php declare(strict_types=1);
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
    public function __construct(string $fileContent, \DateTime $lastModified)
    {
        $this->fileContent  = $fileContent;
        $this->lastModified = $lastModified;
    }

    /**
    * @return boolean
    */
    public function isModified() : bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getBody() : ? string
    {
        return $this->fileContent;
    }

    /**
     * @return iterable
     */
    public function getHeaders() : iterable
    {
        return [];
    }

    /**
     * @param  string $name
     * @return iterable
     */
    public function getHeader(string $name) : iterable
    {
        return [];
    }

    /**
     * @return \DateTime
     */
    public function getLastModified() : ?\DateTime
    {
        return $this->lastModified;
    }
}
