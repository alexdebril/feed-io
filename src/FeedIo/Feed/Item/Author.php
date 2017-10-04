<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;

class Author implements AuthorInterface
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
}
