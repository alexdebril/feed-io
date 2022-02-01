<?php

declare(strict_types=1);

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
    public function getNodeName(): ?string;

    /**
     * @param  string $nodeName
     * @return MediaInterface
     */
    public function setNodeName(string $nodeName): MediaInterface;

    /**
     * @return string
     */
    public function getType(): ?string;

    /**
     * @param  string $type
     * @return MediaInterface
     */
    public function setType(?string $type): MediaInterface;

    /**
     * @return string
     */
    public function getUrl(): ?string;

    /**
     * @param  string $url
     * @return MediaInterface
     */
    public function setUrl(?string $url): MediaInterface;

    /**
     * @return string
     */
    public function getLength(): ?string;

    /**
     * @param  mixed $length
     * @return MediaInterface
     */
    public function setLength($length): MediaInterface;

    /**
     * @return string
     */
    public function getTitle(): ?string;

    /**
     * @param  string $title
     * @return MediaInterface
     */
    public function setTitle(?string $title): MediaInterface;

    /**
     * @return string
     */
    public function getDescription(): ?string;

    /**
     * @param  string $description
     * @return MediaInterface
     */
    public function setDescription(?string $description): MediaInterface;

    /**
     * @return string
     */
    public function getThumbnail(): ?string;

    /**
     * @param  string $thumbnail
     * @return MediaInterface
     */
    public function setThumbnail(?string $thumbnail): MediaInterface;
}
