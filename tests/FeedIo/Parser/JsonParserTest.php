<?php
/**
 * Created by PhpStorm.
 * User: adebril
 * Date: 16/06/17
 * Time: 13:46
 */

namespace FeedIo\Parser;

use FeedIo\Feed;
use FeedIo\Reader\Document;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Json;
use Psr\Log\NullLogger;

use PHPUnit\Framework\TestCase;

class JsonParserTest extends TestCase
{
    public function getDocument()
    {
        $file = dirname(__FILE__)."/../../samples/feed.json";
        return new Document(file_get_contents($file));
    }

    public function testParseContent()
    {
        $parser = new JsonParser(new Json(new DateTimeBuilder()), new NullLogger());
        $feed = new Feed();

        $parser->parse($this->getDocument(), $feed);

        $this->assertEquals('JSON Feed', $feed->getTitle());
        $this->assertEquals('https://jsonfeed.org/graphics/icon.png', $feed->getLogo());
        $this->assertEquals('Brent Simmons and Manton Reece', $feed->getAuthor()->getName());

        $items = $feed->toArray()['items'];

        $this->assertCount(4, $items);
        $this->assertEquals('https://jsonfeed.org/2017/05/17/announcing_json_feed', $items[0]['link']);
        $this->assertNull($items[1]['link']);

        $this->assertNull($items[0]['author']);
        $this->assertNull($items[1]['author']);

        $this->assertCount(1, $items[0]['medias']);
        $this->assertEquals('http://localhost/some-resource.jpg', $items[0]['medias'][0]['url']);
        $this->assertEquals('image/jpg', $items[0]['medias'][0]['type']);

        $this->assertEquals('Manton Reece', $items[2]['author']['name']);
        $this->assertNull($items[2]['author']['uri']);
        $this->assertNull($items[2]['author']['email']);

        $this->assertEquals('Manton Reece', $items[3]['author']['name']);
        $this->assertEquals('http://manton.org', $items[3]['author']['uri']);
        $this->assertEquals('manton@micro.blog', $items[3]['author']['email']);
    }
}
