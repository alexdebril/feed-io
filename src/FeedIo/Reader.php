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

use FeedIo\Adapter\ClientInterface;
use FeedIo\Reader\Result;
use FeedIo\Reader\NoAccurateParserException;
use Psr\Log\LoggerInterface;

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
     * @param ParserAbstract $parser
     * @return $this
     */
    public function addParser(ParserAbstract $parser)
    {
        $this->parsers[] = $parser;

        return $this;
    }

    /**
     * @param $body
     * @return \DOMDocument
     */
    public function loadDocument($body)
    {
        set_error_handler(
            function ($errno, $errstr) {
                throw new \InvalidArgumentException("malformed xml string. parsing error : $errstr ($errno)");
            }
        );

        $domDocument = new \DOMDocument();
        $domDocument->loadXML($body);
        restore_error_handler();

        return $domDocument;
    }

    /**
     * @param $url
     * @param FeedInterface $feed
     * @param \DateTime $modifiedSince
     * @return Result
     */
    public function read($url, FeedInterface $feed, \DateTime $modifiedSince = null)
    {
        if (is_null($modifiedSince)) {
            $modifiedSince = new \DateTime('@0');
        }

        $response = $this->client->getResponse($url, $modifiedSince);
        $document = $this->loadDocument($response->getBody());
        $this->parseDocument($document, $feed);

        return new Result($document, $feed, $modifiedSince, $response, $url);
    }

    /**
     * @param \DOMDocument $document
     * @param FeedInterface $feed
     * @return FeedInterface
     * @throws Parser\UnsupportedFormatException
     * @throws Reader\NoAccurateParserException
     */
    public function parseDocument(\DOMDocument $document, FeedInterface $feed)
    {
        $parser = $this->getAccurateParser($document);
        return $parser->parse($document, $feed);
    }

    /**
     * @param \DOMDocument $document
     * @return ParserAbstract
     * @throws Reader\NoAccurateParserException
     */
    public function getAccurateParser(\DOMDocument $document)
    {
        foreach ($this->parsers as $parser) {
            if ($parser->canHandle($document)) {
                return $parser;
            }
        }

        throw new NoAccurateParserException('No parser can handle this stream');
    }

}