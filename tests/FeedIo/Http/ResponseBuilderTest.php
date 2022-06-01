<?php

namespace FeedIo\Http;

use FeedIo\Feed;
use FeedIo\Feed\Item;
use FeedIo\Formatter\JsonFormatter;
use FeedIo\Formatter\XmlFormatter;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Atom;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ResponseBuilderTest extends TestCase
{
    public function testCreateJsonResponse()
    {
        $responseBuilder = new ResponseBuilder();
        $formatter = new JsonFormatter();
        $feed = $this->getFeed();

        $response = $responseBuilder->createResponse('application/feed+json', $formatter, $feed);

        $headers = $response->getHeaders();
        $this->assertEquals(['Content-Type', 'Cache-Control', 'Last-Modified'], array_keys($headers));
        $this->assertEquals('application/feed+json', $headers['Content-Type'][0]);

        $body = $response->getBody();
        $this->assertJson($body);
    }

    public function testCreateAtomResponse()
    {
        $responseBuilder = new ResponseBuilder();
        $logger = new NullLogger();
        $dateTimeBuilder = new DateTimeBuilder($logger);
        $formatter = new XmlFormatter(new Atom($dateTimeBuilder));
        $feed = $this->getFeed();

        $response = $responseBuilder->createResponse('application/atom+xml', $formatter, $feed);

        $headers = $response->getHeaders();
        $this->assertEquals(['Content-Type', 'Cache-Control', 'Last-Modified'], array_keys($headers));
        $this->assertEquals('application/atom+xml', $headers['Content-Type'][0]);

        $body = $response->getBody();
        $document = new \DOMDocument();
        $document->loadXML($body);

        $this->assertStringStartsWith('<?xml version="1.0" encoding="utf-8"?>', $document->saveXML());
        $this->assertSame(1, $document->getElementsByTagName('entry')->length);
    }

    public function testResponseOnEmptyFeed()
    {
        $responseBuilder = new ResponseBuilder();
        $logger = new NullLogger();
        $dateTimeBuilder = new DateTimeBuilder($logger);
        $formatter = new XmlFormatter(new Atom($dateTimeBuilder));

        $feed = new Feed();
        $feed->setUrl('http://localhost');
        $feed->setTitle('test feed');

        $response = $responseBuilder->createResponse('application/atom+xml', $formatter, $feed);

        $headers = $response->getHeaders();
        $headerNames = array_keys($headers);
        $this->assertEquals(['Content-Type', 'Cache-Control'], $headerNames);
        $this->assertArrayNotHasKey('Last-Modified', $headerNames);
        $this->assertEquals('application/atom+xml', $headers['Content-Type'][0]);

        $body = $response->getBody();
        $document = new \DOMDocument();
        $document->loadXML($body);

        $this->assertStringStartsWith('<?xml version="1.0" encoding="utf-8"?>', $document->saveXML());
        $this->assertSame(0, $document->getElementsByTagName('entry')->length);
    }

    protected function getFeed(): Feed
    {
        $feed = new Feed();
        $feed->setUrl('http://localhost');
        $feed->setLastModified(new \DateTime());
        $feed->setTitle('test feed');

        $item = new Item();
        $item->setLink('http://localhost/item/1');
        $item->setTitle('an item');
        $item->setLastModified(new \DateTime());

        $feed->add($item);

        return $feed;
    }
}
