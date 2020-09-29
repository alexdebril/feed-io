<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Adapter;

use \PHPUnit\Framework\TestCase;

class NullClientTest extends TestCase
{
    public function testGetResponse()
    {
        $client = new NullClient();
        $response = $client->getResponse('', new \DateTime());

        $this->assertInstanceOf('\FeedIo\Adapter\NullResponse', $response);
        $this->assertInstanceOf('\DateTime', $response->getLastModified());
        $this->assertNull($response->getBody());
        $this->assertEquals([], $response->getHeader('foo'));
    }
}
