<?php

declare(strict_types=1);

namespace FeedIo\Adapter;

/**
 * Null HTTP Response
 */
class NullResponse implements ResponseInterface
{
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
     * @return string
     */
    public function getBody(): ?string
    {
        return null;
    }

    /**
    * @return boolean
    */
    public function isModified(): bool
    {
        return true;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified(): ?\DateTime
    {
        return new \DateTime('@0');
    }

    /**
     * @return iterable
     */
    public function getHeaders(): iterable
    {
        return [];
    }

    /**
     * @param  string       $name
     * @return iterable
     */
    public function getHeader(string $name): iterable
    {
        return [];
    }
}
