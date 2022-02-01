<?php

declare(strict_types=1);

namespace FeedIo;

use DateTime;
use FeedIo\Adapter\ClientInterface;
use FeedIo\Adapter\ResponseInterface;
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
    protected array $parsers = [];

    /**
     * @param ClientInterface $client
     * @param LoggerInterface $logger
     */
    public function __construct(
        protected ClientInterface $client,
        protected LoggerInterface $logger
    ) {
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param  ParserAbstract $parser
     * @return Reader
     */
    public function addParser(ParserAbstract $parser): Reader
    {
        $this->logger->debug("new parser added : ".get_class($parser->getStandard()));
        $this->parsers[] = $parser;

        return $this;
    }

    /**
     * @param string $url
     * @param FeedInterface $feed
     * @param DateTime|null $modifiedSince
     * @return Result
     */
    public function read(string $url, FeedInterface $feed, DateTime $modifiedSince = null): Result
    {
        $this->logger->debug("start reading {$url}");
        if (is_null($modifiedSince)) {
            $modifiedSince = new DateTime('1800-01-01');
        }

        try {
            $this->logger->info("hitting {$url}");
            $response = $this->client->getResponse($url, $modifiedSince);
            $document = $this->handleResponse($response, $feed);

            return new Result($document, $feed, $modifiedSince, $response, $url);
        } catch (\Exception $e) {
            $this->logger->warning("{$url} read error : {$e->getMessage()}");
            throw new ReadErrorException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param  ResponseInterface     $response
     * @param  FeedInterface         $feed
     * @return Document
     */
    public function handleResponse(ResponseInterface $response, FeedInterface $feed): Document
    {
        $this->logger->debug("response ok, now turning it into a document");
        $document = new Document($response->getBody());

        if ($response->isModified()) {
            $this->logger->debug("the stream is modified, parsing it");
            $this->parseDocument($document, $feed);
        }

        return $document;
    }

    /**
     * @param Document $document
     * @param FeedInterface $feed
     * @return FeedInterface
     * @throws Parser\UnsupportedFormatException
     * @throws Reader\NoAccurateParserException
     */
    public function parseDocument(Document $document, FeedInterface $feed): FeedInterface
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
    public function getAccurateParser(Document $document): ParserAbstract
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
