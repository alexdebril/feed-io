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
    public function getType();

    /**
     * @param  string $type
     * @return $this
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param  string $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * @return string
     */
    public function getLength();

    /**
     * @param  string $length
     * @return $this
     */
    public function setLength($length);
}
