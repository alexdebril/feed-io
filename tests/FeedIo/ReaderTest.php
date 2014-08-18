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

use Psr\Log\NullLogger;

class ReaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \FeedIo\Reader
     */
    protected $object;

    public function setUp()
    {
        $this->object = new Reader(
            $this->getClientMock(),
            new NullLogger()
        );
    }

    /**
     * @return \FeedIo\Adapter\ClientInterface
     */
    protected function getClientMock()
    {
        return $this->getMock('FeedIo\Adapter\ClientInterface');
    }

    public function testDummy()
    {
        $this->assertInstanceOf('\FeedIo\Reader', $this->object);
    }
}
 