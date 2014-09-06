<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser;


use Psr\Log\NullLogger;

class RssTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\Parser\Rss
     */
    protected $object;

    public function setUp()
    {
        $this->object = new Rss(
            new NullLogger()
        );
    }

    public function testCanHandle()
    {
        $document = $this->buildDomDocument('rss/sample-rss.xml');
        $this->assertTrue($this->object->canHandle($document));
    }

    /**
     * @param $filename
     * @return \DOMDocument
     */
    protected function buildDomDocument($filename)
    {
        $file = dirname(__FILE__) . "/../../samples/{$filename}";
        $domDocument = new \DOMDocument();
        $domDocument->load($file);

        return $domDocument;
    }
}
 