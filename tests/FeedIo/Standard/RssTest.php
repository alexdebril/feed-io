<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/12/14
 * Time: 00:38
 */

namespace FeedIo\Standard;

use FeedIo\Rule\DateTimeBuilder;

use PHPUnit\Framework\TestCase;

class RssTest extends TestCase
{
    public const FORMATTED_DOCUMENT = '<?xml version="1.0" encoding="utf-8"?><rss version="2.0"><channel/></rss>';

    /**
     * @var Atom
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Rss(
            new DateTimeBuilder()
        );
    }

    public function testFormat()
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom = $this->object->format($dom);

        $this->assertEquals(
            str_replace("\n", '', static::FORMATTED_DOCUMENT),
            str_replace("\n", '', $dom->saveXML())
        );
    }
}
