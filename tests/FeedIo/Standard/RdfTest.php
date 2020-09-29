<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Standard;

use FeedIo\Rule\DateTimeBuilder;

use \PHPUnit\Framework\TestCase;

class RdfTest extends TestCase
{
    const FORMATTED_DOCUMENT = '<?xml version="1.0" encoding="utf-8"?><rdf version="1.0"><channel/></rdf>';

    /**
     * @var Rdf
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Rdf(
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
