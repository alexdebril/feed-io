<?php

declare(strict_types=1);

namespace FeedIo\Feed\Item;

use FeedIo\Feed\ArrayableInterface;

class Media implements MediaInterface, ArrayableInterface
{
    protected ?string $nodeName = null;

    protected ?string $type = null;

    protected ?string $url = null;

    protected ?string $length = null;

    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $thumbnail = null;

    /**
     * @return string|null
     */
    public function getNodeName(): ?string
    {
        return $this->nodeName;
    }

    /**
     * @param string $nodeName
     * @return MediaInterface
     */
    public function setNodeName(string $nodeName): MediaInterface
    {
        $this->nodeName = $nodeName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return MediaInterface
     */
    public function setType(?string $type): MediaInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     * @return MediaInterface
     */
    public function setUrl(?string $url): MediaInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLength(): ?string
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     * @return MediaInterface
     */
    public function setLength($length): MediaInterface
    {
        $this->length = (string) intval($length);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return MediaInterface
     */
    public function setTitle(?string $title): MediaInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param  string $description
     * @return MediaInterface
     */
    public function setDescription(?string $description): MediaInterface
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    /**
     * @param  string $thumbnail
     * @return MediaInterface
     */
    public function setThumbnail(?string $thumbnail): MediaInterface
    {
        $this->thumbnail = $thumbnail;

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
