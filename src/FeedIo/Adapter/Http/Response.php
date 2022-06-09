<?php

declare(strict_types=1);

namespace FeedIo\Adapter\Http;

use DateTime;
use FeedIo\Adapter\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * HTTP Response
 */
class Response implements ResponseInterface
{
    public const HTTP_LAST_MODIFIED = 'Last-Modified';

    protected ?string $body = null;

    public function __construct(
        protected PsrResponseInterface $psrResponse,
        protected float $duration
    ) {
    }

    public function getDuration(): float
    {
        return $this->duration;
    }

    public function getStatusCode(): int
    {
        return (int) $this->psrResponse->getStatusCode();
    }

    public function isModified(): bool
    {
        return $this->psrResponse->getStatusCode() != 304 && strlen($this->getBody()) > 0;
    }

    public function getBody(): ?string
    {
        if (is_null($this->body)) {
            $this->body = $this->psrResponse->getBody()->getContents();
        }

        return $this->body;
    }

    public function getLastModified(): ?DateTime
    {
        if ($this->psrResponse->hasHeader(static::HTTP_LAST_MODIFIED)) {
            $lastModified = DateTime::createFromFormat(DateTime::RFC2822, $this->getHeader(static::HTTP_LAST_MODIFIED)[0]);

            return false === $lastModified ? null : $lastModified;
        }

        return null;
    }

    public function getHeaders(): iterable
    {
        return $this->psrResponse->getHeaders();
    }

    public function getHeader(string $name): iterable
    {
        return $this->psrResponse->getHeader($name);
    }
}
