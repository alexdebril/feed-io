<?php

declare(strict_types=1);

namespace FeedIo\Feed;

class StyleSheet
{
    public const DEFAULT_TYPE = 'text/xsl';

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
