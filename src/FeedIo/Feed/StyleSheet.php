<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed;

class StyleSheet
{
    const DEFAULT_TYPE = 'text/xsl';

    public function __construct(
        protected string $href,
        protected string $type = self::DEFAULT_TYPE
    ) {
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
