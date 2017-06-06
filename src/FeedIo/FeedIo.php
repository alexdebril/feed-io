<?php
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
use FeedIo\Reader;
use FeedIo\Reader\FixerSet;
use FeedIo\Reader\FixerAbstract;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Adapter\ClientInterface;
use FeedIo\Standard\Atom;
use FeedIo\Standard\Rss;
use FeedIo\Standard\Rdf;
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

    /**
     * @var \FeedIo\Reader
     */
    protected $reader;

    /**
     * @var \FeedIo\Rule\DateTimeBuilder
     */
    protected $dateTimeBuilder;

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
    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->dateTimeBuilder = new DateTimeBuilder($logger);
        $this->setReader(new Reader($client, $logger));
        $this->loadCommonStandards();
        $this->loadFixerSet();
    }

    /**
     * Loads main standards (RSS, RDF, Atom) in current object's attributes
     *
     * @return $this
     */
    protected function loadCommonStandards()
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
     * @return $this
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->getReader()->addFilter($filter);

        return $this;
    }

    /**
     * Returns main standards
     *
     * @return array
     */
    public function getCommonStandards()
    {
        return array(
            'atom' => new Atom($this->dateTimeBuilder),
            'rss' => new Rss($this->dateTimeBuilder),
            'rdf' => new Rdf($this->dateTimeBuilder),
        );
    }

    /**
     * @param  string                   $name
     * @param  \FeedIo\StandardAbstract $standard
     * @return $this
     */
    public function addStandard($name, StandardAbstract $standard)
    {
        $name = strtolower($name);
        $this->standards[$name] = $standard;
        $this->reader->addParser(
                            new Parser($standard, $this->logger)
                        );

        return $this;
    }

    /**
     * @return \FeedIo\Reader\FixerSet
     */
    public function getFixerSet()
    {
        return $this->fixerSet;
    }

    /**
     * @return $this
     */
    protected function loadFixerSet()
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
     * @return $this
     */
    public function addFixer(FixerAbstract $fixer)
    {
        $fixer->setLogger($this->logger);
        $this->fixerSet->add($fixer);

        return $this;
    }

    /**
     * @return array
     */
    public function getBaseFixers()
    {
        return array(
            new Reader\Fixer\LastModified(),
            new Reader\Fixer\PublicId(),

        );
    }

    /**
     * @param array $formats
     * @return $this
     */
    public function addDateFormats(array $formats)
    {
        foreach( $formats as $format ) {
            $this->getDateTimeBuilder()->addDateFormat($format);
        }

        return $this;
    }

    /**
     * @return \FeedIo\Rule\DateTimeBuilder
     */
    public function getDateTimeBuilder()
    {
        return $this->dateTimeBuilder;
    }

    /**
     * @return \FeedIo\Reader
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * @param \FeedIo\Reader
     * @return $this
     */
    public function setReader(Reader $reader)
    {
        $this->reader = $reader;

        return $this;
    }

    /**
     * @param $url
     * @param  FeedInterface         $feed
     * @param  \DateTime             $modifiedSince
     * @return \FeedIo\Reader\Result
     */
    public function read($url, FeedInterface $feed = null, \DateTime $modifiedSince = null)
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
     * @param $url
     * @param  \DateTime             $modifiedSince
     * @return \FeedIo\Reader\Result
     */
    public function readSince($url, \DateTime $modifiedSince)
    {
        return $this->read($url, new Feed(), $modifiedSince);
    }

    /**
     * @return $this
     */
    public function resetFilters()
    {
        $this->getReader()->resetFilters();

        return $this;
    }

    /**
     * @param  FeedInterface $feed
     * @param  string        $standard Standard's name
     * @return \DomDocument
     */
    public function format(FeedInterface $feed, $standard)
    {
        $this->logAction($feed, "formatting a feed in $standard format");

        $formatter = new Formatter($this->getStandard($standard), $this->logger);

        return $formatter->toDom($feed);
    }

    /**
     * @param  \FeedIo\FeedInterface $feed
     * @return \DomDocument
     */
    public function toRss(FeedInterface $feed)
    {
        return $this->format($feed, 'rss');
    }

    /**
     * @param  \FeedIo\FeedInterface $feed
     * @return \DomDocument
     */
    public function toAtom(FeedInterface $feed)
    {
        return $this->format($feed, 'atom');
    }

    /**
     * @param  string                   $name
     * @return \FeedIo\StandardAbstract
     * @throws \OutOfBoundsException
     */
    public function getStandard($name)
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
     * @return $this
     */
    protected function logAction(FeedInterface $feed, $message)
    {
        $class = get_class($feed);
        $this->logger->debug("$message (feed class : $class)");

        return $this;
    }
}
