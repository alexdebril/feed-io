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

interface MediaContentInterface
{

    /**
     * @return int
     */
    public function getFileSize() : ?int;

    /**
     * @param  string $fileSize
     * @return MediaContentInterface
     */
    public function setFileSize(?int $fileSize) : MediaContentInterface;


    /**
     * @return int
     */
    public function getBitrate() : ?int;

    /**
     * @param  string $bitrate
     * @return MediaContentInterface
     */
    public function setBitrate(?int $bitrate) : MediaContentInterface;


    /**
     * @return int
     */
    public function getFramerate() : ?int;

    /**
     * @param  string $framerate
     * @return MediaContentInterface
     */
    public function setFramerate(?int $framerate) : MediaContentInterface;


    /**
     * @return int
     */
    public function getSamplingrate() : ?float;

    /**
     * @param  string $samplingrate
     * @return MediaContentInterface
     */
    public function setSamplingrate(?float $samplingrate) : MediaContentInterface;


    /**
     * @return int
     */
    public function getDuration() : ?int;

    /**
     * @param  string $duration
     * @return MediaContentInterface
     */
    public function setDuration(?int $duration) : MediaContentInterface;


    /**
     * @return int
     */
    public function getHeight() : ?int;

    /**
     * @param  string $height
     * @return MediaContentInterface
     */
    public function setHeight(?int $height) : MediaContentInterface;


    /**
     * @return int
     */
    public function getWidth() : ?int;

    /**
     * @param  string $width
     * @return MediaContentInterface
     */
    public function setWidth(?int $width) : MediaContentInterface;


    /**
     * @return string
     */
    public function getLang() : ?string;

    /**
     * @param  string $lang
     * @return MediaContentInterface
     */
    public function setLang(?string $lang) : MediaContentInterface;


    /**
     * @return int
     */
    public function getExpression() : ?int;

    /**
     * @param  string $expression
     * @return MediaContentInterface
     */
    public function setExpression(?int $expression) : MediaContentInterface;


    /**
     * @return int
     */
    public function getMedium() : ?int;

    /**
     * @param  string $medium
     * @return MediaContentInterface
     */
    public function setMedium(?int $medium) : MediaContentInterface;

    /**
     * @return bool
     */
    public function isDefault() : bool;

    /**
     * @param bool $default
     * @return MediaContentInterface
     */
    public function setDefault(bool $default) : MediaContentInterface;
}
