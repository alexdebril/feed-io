<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;

interface FormatterInterface
{

    /**
     * @param FeedInterface $feed
     * @return string
     */
    public function toString(FeedInterface $feed) : string;
}
