<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11/12/14
 * Time: 23:34
 */
namespace FeedIo\Standard;

use FeedIo\Rule\DateTimeBuilder;

use \PHPUnit\Framework\TestCase;

class AtomTest extends TestCase
{
    const FORMATTED_DOCUMENT = '<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom"/>';

    /**
     * @var Atom
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Atom(
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
