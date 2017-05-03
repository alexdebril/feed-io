<?php
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
    public function getName();

    /**
     * @param  string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getUri();

    /**
     * @param  string $uri
     * @return $this
     */
    public function setUri($uri);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param  string $email
     * @return $this
     */
    public function setEmail($email);

}
