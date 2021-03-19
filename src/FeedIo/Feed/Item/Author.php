<?php declare(strict_types=1);

namespace FeedIo\Feed\Item;

use FeedIo\Feed\ArrayableInterface;

class Author implements AuthorInterface, ArrayableInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $email;

    /**
     * @return string
     */
    public function getName() : ? string
    {
        return $this->name;
    }

    /**
     * @param  string $name
     * @return AuthorInterface
     */
    public function setName(string $name = null) : AuthorInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri() : ? string
    {
        return $this->uri;
    }

    /**
     * @param  string $uri
     * @return AuthorInterface
     */
    public function setUri(string $uri = null) : AuthorInterface
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail() : ? string
    {
        return $this->email;
    }

    /**
     * @param  string $email
     * @return AuthorInterface
     */
    public function setEmail(string $email = null) : AuthorInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return get_object_vars($this);
    }
}
