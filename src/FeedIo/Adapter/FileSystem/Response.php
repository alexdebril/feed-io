<?php

declare(strict_types=1);

namespace FeedIo\Adapter\FileSystem;

use DateTime;
use FeedIo\Adapter\ResponseInterface;

class Response implements ResponseInterface
{
    public function __construct(
        protected string $fileContent,
        protected DateTime $lastModified
    ) {
    }

    /**
     * @return float
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
    public function isModified(): bool
    {
        return true;
    }

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->fileContent;
    }

    /**
     * @return iterable
     */
    public function getHeaders(): iterable
    {
        return [];
    }

    /**
     * @param  string $name
     * @return iterable
     */
    public function getHeader(string $name): iterable
    {
        return [];
    }

    /**
     * @return DateTime|null
     */
    public function getLastModified(): ?DateTime
    {
        return $this->lastModified;
    }
}
