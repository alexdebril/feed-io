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

interface MediaEmbedInterface
{
    /**
     * @return string
     */
    public function getUrl() : ?string;

    /**
     * @param  string $url
     * @return MediaEmbedInterface
     */
    public function setUrl(?string $url) : MediaEmbedInterface;


    /**
     * @return int
     */
    public function getWidth() : ?int;

    /**
     * @param  string $width
     * @return MediaEmbedInterface
     */
    public function setWidth(?int $width) : MediaEmbedInterface;


    /**
     * @return int
     */
    public function getHeight() : ?int;

    /**
     * @param  string $height
     * @return MediaEmbedInterface
     */
    public function setHeight(?int $height) : MediaEmbedInterface;


    /**
     * @return array
     */
    public function getParams() : array;

    /**
     * @param  string $params
     * @return MediaEmbedInterface
     */
    public function setParams(array $params) : MediaEmbedInterface;
}
