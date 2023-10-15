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
        $feeds = $this->extractFeeds($stream->getBody(), $url);

        libxml_use_internal_errors($internalErrors);

        return $feeds;
    }

    protected function extractFeeds(string $html, string $url = null): array    
    {
        $dom = new DOMDocument();
        $dom->loadHTML($html);

        $links = $dom->getElementsByTagName('link');
        $feeds = [];
        foreach ($links as $link) {
            if ($this->isFeedLink($link)) {
                $href = $link->getAttribute('href');
                if (strpos($href, '//') === 0) {
                    // Link href is protocol-less, Implies feed supports http
                    // and https. Consumers will often assume that feed url
                    // includes protocol, so we will assign a protocol before
                    // returning
                    $href = 'https:' . $href;
                }
                if (!parse_url($href, PHP_URL_HOST) && $url){
                    $href = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST) . '/' . ltrim($href,'/');
                }
                $feeds[] = $href;
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
