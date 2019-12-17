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

interface MediaStatusInterface
{
    /**
     * @return int
     */
    public function getValue() : ?int;

    /**
     * @param  int $value
     * @return MediaValueInterface
     */
    public function setValue(?int $value) : MediaStatusInterface;

    /**
     * @return string
     */
    public function getReason() : ?string;

    /**
     * @param  string $reason
     * @return MediaStatusInterface
     */
    public function setReason(?string $reason) : MediaStatusInterface;
}
