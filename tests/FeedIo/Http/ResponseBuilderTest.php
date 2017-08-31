<?php
namespace FeedIo\Http;

use \PHPUnit\Framework\TestCase;
use FeedIo\Feed;
use FeedIo\Feed\Item;
use FeedIo\Formatter\JsonFormatter;
use FeedIo\Formatter\XmlFormatter;
use FeedIo\Standard\Atom;
use FeedIo\Rule\DateTimeBuilder;
use Psr\Log\NullLogger;

class ResponseBuilderTest extends TestCase
{
    public function testCreateJsonResponse()
    {
        $responseBuilder = new ResponseBuilder();
        $formatter = new JsonFormatter();
        $feed = $this->getFeed();

        $response = $responseBuilder->createResponse('json', $formatter, $feed);
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);

        $headers = $response->getHeaders();
        $this->assertEquals(['Content-Type', 'Cache-Control', 'Last-Modified'], array_keys($headers));
        $this->assertEquals('application/json', $headers['Content-Type'][0]);

        $body = $response->getBody()->getContents();
        $this->assertInternalType('array', json_decode($body, true));
    }

    public function testCreateAtomResponse()
    {
        $responseBuilder = new ResponseBuilder();
        $logger = new NullLogger();
        $dateTimeBuilder = new DateTimeBuilder($logger);
        $formatter = new XmlFormatter(new Atom($dateTimeBuilder));
        $feed = $this->getFeed();

        $response = $responseBuilder->createResponse('atom', $formatter, $feed);
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);

        $headers = $response->getHeaders();
        $this->assertEquals(['Content-Type', 'Cache-Control', 'Last-Modified'], array_keys($headers));
        $this->assertEquals('application/xhtml+xml', $headers['Content-Type'][0]);

        $body = $response->getBody()->getContents();
        $document = new \DOMDocument();
        $document->loadXML($body);

        $this->assertStringStartsWith('<?xml version="1.0" encoding="utf-8"?>', $document->saveXML());
    }

    protected function getFeed() : \FeedIo\Feed
    {
        $feed = new Feed();
        $feed->setUrl('http://localhost');
        $feed->setLastModified(new \DateTime);
        $feed->setTitle('test feed');

        $item = new Item();
        $item->setLink('http://localhost/item/1');
        $item->setTitle('an item');
        $item->setLastModified(new \DateTime());
        $item->setDescription('lorem ipsum');

        $feed->add($item);

        return $feed;
    }
}
