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

use \PHPUnit\Framework\TestCase;

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
    }
}
