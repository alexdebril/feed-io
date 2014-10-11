<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed;

/**
 * Interface NodeInterface
 * A node in the news feed
 * @package FeedIo\Feed
 */
interface NodeInterface
{
    /**
     * Returns node's title
     * @return string
     */
    public function getTitle();

    /**
     * Sets nodes's title
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Returns node's public id
     * @return string
     */
    public function getPublicId();

    /**
     * sets node's public id
     * @param string $id
     * @return $this
     */
    public function setPublicId($id);

    /**
     * Returns node's description
     * @return string
     */
    public function getDescription();

    /**
     * Sets node's description
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Returns the node's last modified date
     * @return \DateTime
     */
    public function getLastModified();

    /**
     * Sets the node's last modified date
     * @param \DateTime $lastModified
     * @return $this
     */
    public function setLastModified(\DateTime $lastModified);

    /**
     * Returns the node's link
     * @return string
     */
    public function getLink();

    /**
     * Sets the nodes's link
     * @param string $link
     * @return $this
     */
    public function setLink($link);

}
