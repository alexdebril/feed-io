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

abstract class MediaContentMedium extends MediaConstant
{
    const Image = 1;
    const Audio = 2;
    const Video = 3;
    const Document = 4;
    const Executable = 5;

    const VALUES = array(
        "image" => MediaContentMedium::Image,
        "audio" => MediaContentMedium::Audio,
        "video" => MediaContentMedium::Video,
        "document" => MediaContentMedium::Document,
        "executable" => MediaContentMedium::Executable,
    );
}

abstract class MediaContentExpression extends MediaConstant
{
    const Sample = 1;
    const Full = 2;
    const NonStop = 3;

    const VALUES = array(
        "sample" => MediaContentExpression::Sample,
        "full" => MediaContentExpression::Full,
        "nonstop" => MediaContentExpression::NonStop,
    );
}

class MediaContent implements MediaContentInterface
{
    /**
     * @var int
     */
    protected $fileSize;

    /**
     * @var int
     */
    protected $bitrate;

    /**
     * @var int
     */
    protected $framerate;

    /**
     * @var float
     */
    protected $samplingrate;

    /**
     * @var int
     */
    protected $duration;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var int
     */
    protected $expression;

    /**
     * @var int
     */
    protected $medium;

    /**
     * @var bool
     */
    protected $default = true;

    /**
     * @return int
     */
    public function getFileSize() : ?int
    {
        return $this->fileSize;
    }

    /**
     * @param  int $fileSize
     * @return MediaContentInterface
     */
    public function setFileSize(?int $fileSize) : MediaContentInterface
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getBitrate() : ?int
    {
        return $this->bitrate;
    }

    /**
     * @param  int $bitrate
     * @return MediaContentInterface
     */
    public function setBitrate(?int $bitrate) : MediaContentInterface
    {
        $this->bitrate = $bitrate;

        return $this;
    }

    /**
     * @return int
     */
    public function getFramerate() : ?int
    {
        return $this->framerate;
    }

    /**
     * @param  int $framerate
     * @return MediaContentInterface
     */
    public function setFramerate(?int $framerate) : MediaContentInterface
    {
        $this->framerate = $framerate;

        return $this;
    }

    /**
     * @return integer
     */
    public function getSamplingrate() : ?float
    {
        return $this->samplingrate;
    }

    /**
     * @param  float $samplingrate
     * @return MediaContentInterface
     */
    public function setSamplingrate(?float $samplingrate) : MediaContentInterface
    {
        $this->samplingrate = $samplingrate;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration() : ?int
    {
        return $this->duration;
    }

    /**
     * @param  int $duration
     * @return MediaContentInterface
     */
    public function setDuration(?int $duration) : MediaContentInterface
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight() : ?int
    {
        return $this->height;
    }

    /**
     * @param  int $height
     * @return MediaContentInterface
     */
    public function setHeight(?int $height) : MediaContentInterface
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth() : ?int
    {
        return $this->width;
    }

    /**
     * @param  int $width
     * @return MediaContentInterface
     */
    public function setWidth(?int $width) : MediaContentInterface
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return string
     */
    public function getLang() : ?string
    {
        return $this->lang;
    }

    /**
     * @param  string $lang
     * @return MediaContentInterface
     */
    public function setLang(?string $lang) : MediaContentInterface
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpression() : ?int
    {
        return $this->expression;
    }

    /**
     * @param  int $expression
     * @return MediaContentInterface
     */
    public function setExpression(?int $expression) : MediaContentInterface
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * @return int
     */
    public function getMedium() : ?int
    {
        return $this->medium;
    }

    /**
     * @param  int $medium
     * @return MediaContentInterface
     */
    public function setMedium(?int $medium) : MediaContentInterface
    {
        $this->medium = $medium;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault() : bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     * @return MediaContentInterface
     */
    public function setDefault(bool $default) : MediaContentInterface
    {
        $this->default = $default;

        return $this;
    }
}
