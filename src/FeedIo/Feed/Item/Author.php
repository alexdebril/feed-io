<?php

declare(strict_types=1);

namespace FeedIo\Feed\Item;

use FeedIo\Feed\ArrayableInterface;

class Author implements AuthorInterface, ArrayableInterface
{
    protected ?string $name = null;

    protected ?string $uri = null;

    protected ?string $email = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return AuthorInterface
     */
    public function setName(string $name = null): AuthorInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * @param string|null $uri
     * @return AuthorInterface
     */
    public function setUri(string $uri = null): AuthorInterface
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return AuthorInterface
     */
    public function setEmail(string $email = null): AuthorInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
