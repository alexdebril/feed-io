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

abstract class MediaHashAlgo extends MediaConstant
{
    const MD5 = 1;
    const SHA1 = 2;

    const VALUES = array(
        null => MediaHashAlgo::MD5,
        "md5" => MediaHashAlgo::MD5,
        "sha1" => MediaHashAlgo::SHA1,
    );
}


class MediaHash implements MediaHashInterface
{
    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $algo;

    /**
     * @return string
     */
    public function getContent() : ?string
    {
        return $this->content;
    }

    /**
     * @param  string $content
     * @return MediaHashInterface
     */
    public function setContent(?string $content) : MediaHashInterface
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getAlgo() : ?int
    {
        return $this->algo;
    }

    /**
     * @param  int $algo
     * @return MediaHashInterface
     */
    public function setAlgo(?int $algo) : MediaHashInterface
    {
        $this->algo = $algo;

        return $this;
    }
}
