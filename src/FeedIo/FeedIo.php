<?php

declare(strict_types=1);

namespace FeedIo;

use DateTime;
use FeedIo\Reader\Result;
use FeedIo\Rule\DateTimeBuilderInterface;
use FeedIo\Adapter\ClientInterface;
use FeedIo\Http\ResponseBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * This class acts as a facade. It provides methods to access feed-io main features
 *
 * <code>
 *   // $client is a \FeedIo\Adapter\ClientInterface instance, $logger a \Psr\Log\LoggerInterface
 *   $feedIo = new FeedIo($client, $logger);
 *
 *   // read a feed. Output is a Result instance
 *   $result = $feedIo->read('http://somefeed.org/feed.rss');
 *
 *   // use the feed
 *   $feed = $result->getFeed();
 *   echo $feed->getTitle();
 *
 *   // and its items
 *   foreach ( $feed as $item ) {
 *       echo $item->getTitle();
 *       echo $item->getDescription();
 *   }
 *
 * </code>
 *
 * <code>
 *   // build the feed to publish
 *   $feed = new \FeedIo\Feed;
 *   $feed->setTitle('title');
 *   // ...
 *
 *   // add items to it
 *   $item = new \FeedIo\Feed\Item
 *   $item->setTitle('my great post');
 *
 *   // want to publish a media ? no problem
 *   $media = new \FeedIo\Feed\Item\Media
 *   $media->setUrl('http://yourdomain.tld/medias/some-podcast.mp3');
 *   $media->setType('audio/mpeg');
 *
 *   // add it to the item
 *   $item->addMedia($media);
 *
 *   // add the item to the feed (almost there)
 *   $feed->add($item);
 *
 *   // format it in atom
 *   $feedIo->toAtom($feed);
 * </code>
 *
 */
class FeedIo
{
    protected Reader $reader;

    public function __construct(
        protected ClientInterface $client,
        protected LoggerInterface $logger,
        protected ?SpecificationInterface $specification = null
    ) {
        if (is_null($this->specification)) {
            $this->specification = new Specification($this->logger);
        }
        $this->reader = new Reader($this->client, $this->logger);
        foreach ($this->specification->getAllStandards() as $standard) {
            $parser = $this->specification->newParser($standard->getSyntaxFormat(), $standard);
            $this->reader->addParser($parser);
        }
    }

    /**
     * Adds a standard to the Specification.
     *
     * @see Specification
     * @param string $name
     * @param StandardAbstract $standard
     * @return $this
     */
    public function addStandard(string $name, StandardAbstract $standard): self
    {
        $this->specification->addStandard($name, $standard);

        return $this;
    }

    /**
     * Discover feeds from the webpage's headers
     *
     * @param  string $url
     * @return array
     */
    public function discover(string $url): array
    {
        $explorer = new Explorer($this->client, $this->logger);

        return $explorer->discover($url);
    }

    /**
     * Read the feed hosted at `$url` and populate the `$feed` accordingly.
     * `$modifiedSince` is used to return an empty result if the HTTP's response is 'not modified'
     *
     * @param string $url
     * @param FeedInterface|null $feed
     * @param DateTime|null $modifiedSince
     * @return Result
     */
    public function read(string $url, FeedInterface $feed = null, DateTime $modifiedSince = null): Result
    {
        if (is_null($feed)) {
            $feed = new Feed();
        }

        $this->logAction($feed, "read access : $url into a feed instance");
        $result = $this->reader->read($url, $feed, $modifiedSince);

        $this->specification->getFixerSet()->correct($result);

        return $result;
    }

    /**
     * Get a PSR-7 compliant response for the given feed
     *
     * @param FeedInterface $feed
     * @param string $standard
     * @param int $maxAge
     * @param bool $public
     * @return ResponseInterface
     */
    public function getPsrResponse(FeedInterface $feed, string $standard, int $maxAge = 600, bool $public = true): ResponseInterface
    {
        $this->logAction($feed, "creating a PSR 7 Response in $standard format");

        $formatter = $this->specification->getStandard($standard)->getFormatter();
        $responseBuilder = new ResponseBuilder($maxAge, $public);

        return $responseBuilder->createResponse($standard, $formatter, $feed);
    }

    /**
     * Return the feed in the XML or JSON format according to the `$standard` value (can be "rss", "atom" or "json").
     *
     * @param  FeedInterface $feed
     * @param  string        $standard Standard's name
     * @return string
     */
    public function format(FeedInterface $feed, string $standard): string
    {
        $this->logAction($feed, "formatting a feed in $standard format");

        $formatter = $this->specification->getStandard($standard)->getFormatter();

        return $formatter->toString($feed);
    }

    /**
     * Convert to RSS format
     *
     * @param FeedInterface $feed
     * @return string
     */
    public function toRss(FeedInterface $feed): string
    {
        return $this->format($feed, 'rss');
    }

    /**
     * Convert to Atom
     *
     * @param FeedInterface $feed
     * @return string
     */
    public function toAtom(FeedInterface $feed): string
    {
        return $this->format($feed, 'atom');
    }

    /**
     * Convert to JSON Feed
     *
     * @param FeedInterface $feed
     * @return string
     */
    public function toJson(FeedInterface $feed): string
    {
        return $this->format($feed, 'json');
    }

    /**
     * Add a new item to the list of the crooked date formats supported
     *
     * @param array $formats
     * @return $this
     */
    public function addDateFormats(array $formats): FeedIo
    {
        foreach ($formats as $format) {
            $this->getDateTimeBuilder()->addDateFormat($format);
        }

        return $this;
    }

    /**
     * Get a direct access to the DateTime builder
     *
     * @return DateTimeBuilderInterface
     */
    public function getDateTimeBuilder(): DateTimeBuilderInterface
    {
        return $this->specification->getDateTimeBuilder();
    }

    /**
     * Get the Reader used by feed-io
     *
     * @return Reader
     */
    public function getReader(): Reader
    {
        return $this->reader;
    }

    /**
     * Log what just happened
     *
     * @param FeedInterface $feed
     * @param string $message
     * @return $this
     */
    protected function logAction(FeedInterface $feed, string $message): FeedIo
    {
        $class = get_class($feed);
        $this->logger->debug("$message (feed class : $class)");

        return $this;
    }
}
