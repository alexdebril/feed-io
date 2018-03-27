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
 * Describe a Media instance
 *
 * most of the time medias are defined as enclosure in the XML document
 *
 * Atom :
 *     <link rel="enclosure" href="http://example.org/video.mpeg" type="video/mpeg" />
 *
 * RSS :
 *     <enclosure url="http://example.org/video.mpeg" length="12216320" type="video/mpeg" />
 *
 * <code>
 *     // will display http://example.org/video.mpeg
 *     echo $media->getUrl();
 * </code>
 */
interface MediaInterface
{
    /**
     * @return string
     */
    public function getNodeName() : string;

    /**
     * @param  string $nodeName
     * @return MediaInterface
     */
    public function setNodeName(string $nodeName) : MediaInterface;

    /**
     * @return bool
     */
    public function isThumbnail() : bool;

    /**
     * @return string
     */
    public function getType() : ? string;

    /**
     * @param  string $type
     * @return MediaInterface
     */
    public function setType(?string $type) : MediaInterface;

    /**
     * @return string
     */
    public function getUrl() : ? string;

    /**
     * @param  string $url
     * @return MediaInterface
     */
    public function setUrl(?string $url) : MediaInterface;

    /**
     * @return string
     */
    public function getLength() : ? string;

    /**
     * @param  string $length
     * @return MediaInterface
     */
    public function setLength(?string $length) : MediaInterface;
}
