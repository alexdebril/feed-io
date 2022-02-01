<?php

declare(strict_types=1);

namespace FeedIo;

use DateTime;
use DomDocument;
use DomElement;
use FeedIo\Adapter\ClientInterface;
use Psr\Log\LoggerInterface;

class Explorer
{
    public const VALID_TYPES = [
        'application/atom+xml',
        'application/rss+xml'
    ];

    public function __construct(
        protected ClientInterface $client,
        protected LoggerInterface $logger
    ) {
    }

    public function discover(string $url): array
    {
        $this->logger->info("discover feeds from {$url}");
        $stream = $this->client->getResponse($url, new DateTime('@0'));

        $internalErrors = libxml_use_internal_errors(true);
        $feeds = $this->extractFeeds($stream->getBody());

        libxml_use_internal_errors($internalErrors);

        return $feeds;
    }

    protected function extractFeeds(string $html): array
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html);

        $links = $dom->getElementsByTagName('link');
        $feeds = [];
        foreach ($links as $link) {
            if ($this->isFeedLink($link)) {
                $feeds[] = $link->getAttribute('href');
            }
        }

        return $feeds;
    }

    protected function isFeedLink(DomElement $element): bool
    {
        return $element->hasAttribute('type')
                && in_array($element->getAttribute('type'), self::VALID_TYPES);
    }
}
