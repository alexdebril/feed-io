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

use FeedIo\Adapter\ClientInterface;
use Psr\Log\LoggerInterface;

class Explorer
{

    /**
     * @var \FeedIo\Adapter\ClientInterface;
     */
    protected $client;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    const VALID_TYPES = [
        'application/atom+xml',
        'application/rss+xml'
    ];

    /**
     * @param \FeedIo\Adapter\ClientInterface $client
     * @param \Psr\Log\LoggerInterface        $logger
     */
    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Discover feeds from the webpage's headers
     * @param  string $url
     * @return array
     */
    public function discover(string $url) : array
    {
        $this->logger->info("discover feeds from {$url}");
        $stream = $this->client->getResponse($url, new \DateTime);

        $internalErrors = libxml_use_internal_errors(true);
        $entityLoaderDisabled = libxml_disable_entity_loader(true);

        $feeds = $this->extractFeeds($stream->getBody());

        libxml_use_internal_errors($internalErrors);
        libxml_disable_entity_loader($entityLoaderDisabled);

        return $feeds;
    }

    /**
     * Extract feeds Urls from HTML stream
     * @param  string $html
     * @return array
     */
    protected function extractFeeds(string $html) : array
    {
        $dom = new \DOMDocument();
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

    /**
     * Tells if the given Element contains a valid Feed Url
     * @param  DomElement $element
     * @return bool
     */
    protected function isFeedLink(\DomElement $element) : bool
    {
        return $element->hasAttribute('type')
                && in_array($element->getAttribute('type'), self::VALID_TYPES);
    }
}
