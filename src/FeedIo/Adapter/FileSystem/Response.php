<?php declare(strict_types=1);

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
     * @return int
     */
    public function getDuration(): float
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return 0;
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
