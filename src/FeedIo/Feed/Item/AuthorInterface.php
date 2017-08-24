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

/**
 * Describe a Author instance
 *
 */
interface AuthorInterface
{

    /**
     * @return string
     */
    public function getName() : ? string;

    /**
     * @param  string $name
     * @return AuthorInterface
     */
    public function setName(string $name = null) : AuthorInterface;

    /**
     * @return string
     */
    public function getUri() : ? string;

    /**
     * @param  string $uri
     * @return AuthorInterface
     */
    public function setUri(string $uri = null) : AuthorInterface;

    /**
     * @return string
     */
    public function getEmail() : ? string;

    /**
     * @param  string $email
     * @return AuthorInterface
     */
    public function setEmail(string $email = null) : AuthorInterface;
}
