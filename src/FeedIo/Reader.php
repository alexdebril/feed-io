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

use FeedIo\ParserAbstract;
use FeedIo\Adapter\ClientInterface;
use FeedIo\Reader\Document;
use FeedIo\Reader\ReadErrorException;
use FeedIo\Reader\Result;
use FeedIo\Reader\NoAccurateParserException;
use Psr\Log\LoggerInterface;

/**
 * Consumes feeds and return corresponding Result instances
 *
 * Depends on :
 *  - FeedIo\Adapter\ClientInterface
 *  - Psr\Log\LoggerInterface
 *
 * A Reader instance MUST have at least one parser added with the addParser() method to read feeds
 * It will throw a NoAccurateParserException if it cannot find a suitable parser for the feed.
 */
class Reader
{
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
    protected $parsers = array();

    /**
     * @param ClientInterface $client
     * @param LoggerInterface $logger
     */
    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * @param  Parser $parser
     * @return $this
     */
    public function addParser(ParserAbstract $parser)
    {
        $this->logger->debug("new parser added : ".get_class($parser->getStandard()));
        $this->parsers[] = $parser;

        return $this;
    }

    /**
     * adds a filter to every parsers
     *
     * @param \FeedIo\FilterInterface $filter
     * @return $this
     */
    public function addFilter(FilterInterface $filter)
    {
        foreach ($this->parsers as $parser) {
            $parser->addFilter($filter);
        }

        return $this;
    }

    /**
     * Reset filters on every parsers
     * @return $this
     */
    public function resetFilters()
    {
        foreach ($this->parsers as $parser) {
            $parser->resetFilters();
        }

        return $this;
    }

    /**
     * @param $url
     * @param  FeedInterface         $feed
     * @param  \DateTime             $modifiedSince
     * @return \FeedIo\Reader\Result
     * @throws ReadErrorException
     */
    public function read($url, FeedInterface $feed, \DateTime $modifiedSince = null)
    {
        $this->logger->debug("start reading {$url}");
        if (is_null($modifiedSince)) {
            $this->logger->notice("no 'modifiedSince' parameter given, setting it to 01/01/1970");
            $modifiedSince = new \DateTime('@0');
        }

        try {
            $response = $this->client->getResponse($url, $modifiedSince);

            $this->logger->debug("response ok, now turning it into a DomDocument");
            $document = new Document($response->getBody());
            $this->parseDocument($document, $feed);

            $this->logger->info("{$url} successfully parsed");

            return new Result($document, $feed, $modifiedSince, $response, $url);
        } catch (\Exception $e) {
            $this->logger->warning("{$url} read error : {$e->getMessage()}");
            throw new ReadErrorException($e);
        }
    }

    /**
     * @param Document $document
     * @param FeedInterface $feed
     * @return FeedInterface
     * @throws Parser\UnsupportedFormatException
     * @throws Reader\NoAccurateParserException
     */
    public function parseDocument(Document $document, FeedInterface $feed)
    {
        $parser = $this->getAccurateParser($document);
        $this->logger->debug("accurate parser : ".get_class($parser));

        return $parser->parse($document, $feed);
    }

    /**
     * @param  Document                     $document
     * @return ParserAbstract
     * @throws Reader\NoAccurateParserException
     */
    public function getAccurateParser(Document $document)
    {
        foreach ($this->parsers as $parser) {
            if ($parser->getStandard()->canHandle($document)) {
                return $parser;
            }
        }

        $message = 'No parser can handle this stream';
        $this->logger->error($message);
        throw new NoAccurateParserException($message);
    }
}
