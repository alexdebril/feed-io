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

use FeedIo\Filter\ModifiedSince;
use FeedIo\Reader\Result;
use FeedIo\Reader\FixerSet;
use FeedIo\Reader\FixerAbstract;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Rule\DateTimeBuilderInterface;
use FeedIo\Adapter\ClientInterface;
use FeedIo\Standard\Loader;
use FeedIo\Async\Reader as AsyncReader;
use FeedIo\Async\CallbackInterface;
use FeedIo\FeedInterface;
use Psr\Log\LoggerInterface;
use FeedIo\Http\ResponseBuilder;
use Psr\Http\Message\ResponseInterface;

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

    /**
     * @var \FeedIo\Reader
     */
    protected $reader;

    /**
     * @var \FeedIo\Rule\DateTimeBuilder
     */
    protected $dateTimeBuilder;

    /**
     * @var \FeedIo\Adapter\ClientInterface;
     */
    protected $client;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $standards;

    /**
     * @var \FeedIo\Reader\FixerSet
     */
    protected $fixerSet;

    /**
     * @param \FeedIo\Adapter\ClientInterface $client
     * @param \Psr\Log\LoggerInterface        $logger
     */
    public function __construct(ClientInterface $client, LoggerInterface $logger, DateTimeBuilderInterface $dateTimeBuilder = null)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->dateTimeBuilder = $dateTimeBuilder ?? new DateTimeBuilder($logger);
        $this->setReader(new Reader($client, $logger));
        $this->loadCommonStandards();
        $this->loadFixerSet();
    }

    /**
     * Loads main standards (RSS, RDF, Atom) in current object's attributes
     *
     * @return FeedIo
     */
    protected function loadCommonStandards() : FeedIo
    {
        $standards = $this->getCommonStandards();
        foreach ($standards as $name => $standard) {
            $this->addStandard($name, $standard);
        }

        return $this;
    }

    /**
     * adds a filter to the reader
     *
     * @param \FeedIo\FilterInterface $filter
     * @return FeedIo
     */
    public function addFilter(FilterInterface $filter) : FeedIo
    {
        $this->getReader()->addFilter($filter);

        return $this;
    }

    /**
     * Returns main standards
     *
     * @return array
     */
    public function getCommonStandards() : array
    {
        $loader = new Loader();

        return $loader->getCommonStandards($this->getDateTimeBuilder());
    }

    /**
     * @param  string                   $name
     * @param  \FeedIo\StandardAbstract $standard
     * @return FeedIo
     */
    public function addStandard(string $name, StandardAbstract $standard) : FeedIo
    {
        $name = strtolower($name);
        $this->standards[$name] = $standard;
        $parser = $this->newParser($standard->getSyntaxFormat(), $standard);
        $this->reader->addParser($parser);

        return $this;
    }

    /**
     * @param string $format
     * @param StandardAbstract $standard
     * @return ParserAbstract
     */
    public function newParser(string $format, StandardAbstract $standard) : ParserAbstract
    {
        $reflection = new \ReflectionClass("FeedIo\\Parser\\{$format}Parser");

        if (! $reflection->isSubclassOf('FeedIo\ParserAbstract')) {
            throw new \InvalidArgumentException();
        }

        return $reflection->newInstanceArgs([$standard, $this->logger]);
    }

    /**
     * @return \FeedIo\Reader\FixerSet
     */
    public function getFixerSet() : FixerSet
    {
        return $this->fixerSet;
    }

    /**
     * @return FeedIo
     */
    protected function loadFixerSet() : FeedIo
    {
        $this->fixerSet = new FixerSet();
        $fixers = $this->getBaseFixers();

        foreach ($fixers as $fixer) {
            $this->addFixer($fixer);
        }

        return $this;
    }

    /**
     * @param  FixerAbstract $fixer
     * @return FeedIo
     */
    public function addFixer(FixerAbstract $fixer) : FeedIo
    {
        $fixer->setLogger($this->logger);
        $this->fixerSet->add($fixer);

        return $this;
    }

    /**
     * @return array
     */
    public function getBaseFixers() : array
    {
        return array(
            new Reader\Fixer\LastModified(),
            new Reader\Fixer\PublicId(),
        );
    }

    /**
     * @param array $formats
     * @return FeedIo
     */
    public function addDateFormats(array $formats) : FeedIo
    {
        foreach ($formats as $format) {
            $this->getDateTimeBuilder()->addDateFormat($format);
        }

        return $this;
    }

    /**
     * @return \FeedIo\Rule\DateTimeBuilder
     */
    public function getDateTimeBuilder() : DateTimeBuilder
    {
        return $this->dateTimeBuilder;
    }

    /**
     * @return \FeedIo\Reader
     */
    public function getReader() : Reader
    {
        return $this->reader;
    }

    /**
     * @param \FeedIo\Reader $reader
     * @return FeedIo
     */
    public function setReader(Reader $reader) : FeedIo
    {
        $this->reader = $reader;

        return $this;
    }

    /**
     * Discover feeds from the webpage's headers
     * @param  string $url
     * @return array
     */
    public function discover(string $url) : array
    {
        $explorer = new Explorer($this->client, $this->logger);

        return $explorer->discover($url);
    }

    /**
     * @param iterable $requests
     * @param CallbackInterface $callback
     * @param string $feedClass
     */
    public function readAsync(iterable $requests, CallbackInterface $callback, string $feedClass = '\FeedIo\Feed') : void
    {
        $reader = new AsyncReader($this->reader, $this->reader->getClient(), $callback, $feedClass);

        $reader->process($requests);
    }

    /**
     * @param  string                $url
     * @param  FeedInterface         $feed
     * @param  \DateTime             $modifiedSince
     * @return \FeedIo\Reader\Result
     */
    public function read(string $url, FeedInterface $feed = null, \DateTime $modifiedSince = null) : Result
    {
        if (is_null($feed)) {
            $feed = new Feed();
        }

        if ($modifiedSince instanceof \DateTime) {
            $this->addFilter(new ModifiedSince($modifiedSince));
        }

        $this->logAction($feed, "read access : $url into a feed instance");
        $result = $this->reader->read($url, $feed, $modifiedSince);

        $this->fixerSet->correct($result->getFeed());

        return $result;
    }

    /**
     * @param  string                $url
     * @param  \DateTime             $modifiedSince
     * @return \FeedIo\Reader\Result
     */
    public function readSince(string $url, \DateTime $modifiedSince) : Result
    {
        return $this->read($url, new Feed(), $modifiedSince);
    }

    /**
     * @return FeedIo
     */
    public function resetFilters() : FeedIo
    {
        $this->getReader()->resetFilters();

        return $this;
    }

    /**
     * Get a PSR-7 compliant response for the given feed
     *
     * @param \FeedIo\FeedInterface $feed
     * @param string $standard
     * @param int $maxAge
     * @param bool $public
     * @return ResponseInterface
     */
    public function getPsrResponse(FeedInterface $feed, string $standard, int $maxAge = 600, bool $public = true) : ResponseInterface
    {
        $this->logAction($feed, "creating a PSR 7 Response in $standard format");

        $formatter = $this->getStandard($standard)->getFormatter();
        $responseBuilder = new ResponseBuilder($maxAge, $public);

        return $responseBuilder->createResponse($standard, $formatter, $feed);
    }

    /**
     * @param  FeedInterface $feed
     * @param  string        $standard Standard's name
     * @return string
     */
    public function format(FeedInterface $feed, string $standard) : string
    {
        $this->logAction($feed, "formatting a feed in $standard format");

        $formatter = $this->getStandard($standard)->getFormatter();

        return $formatter->toString($feed);
    }

    /**
     * @param  \FeedIo\FeedInterface $feed
     * @return string
     */
    public function toRss(FeedInterface $feed) : string
    {
        return $this->format($feed, 'rss');
    }

    /**
     * @param  \FeedIo\FeedInterface $feed
     * @return string
     */
    public function toAtom(FeedInterface $feed) : string
    {
        return $this->format($feed, 'atom');
    }

    /**
     * @param  \FeedIo\FeedInterface $feed
     * @return string
     */
    public function toJson(FeedInterface $feed) : string
    {
        return $this->format($feed, 'json');
    }


    /**
     * @param  string                   $name
     * @return \FeedIo\StandardAbstract
     * @throws \OutOfBoundsException
     */
    public function getStandard(string $name) : StandardAbstract
    {
        $name = strtolower($name);
        if (array_key_exists($name, $this->standards)) {
            return $this->standards[$name];
        }

        throw new \OutOfBoundsException("no standard found for $name");
    }

    /**
     * @param  \FeedIo\FeedInterface $feed
     * @param  string                $message
     * @return FeedIo
     */
    protected function logAction(FeedInterface $feed, string $message) : FeedIo
    {
        $class = get_class($feed);
        $this->logger->debug("$message (feed class : $class)");

        return $this;
    }
}
