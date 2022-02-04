<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;

use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase
{
    /**
     * @var \FeedIo\Feed\Item\Author
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Author();
    }

    public function testSetName()
    {
        $this->object->setName('John Doe');
        $this->assertEquals('John Doe', $this->object->getName());
    }

    public function testSetUri()
    {
        $this->object->setUri('http://localhost');
        $this->assertEquals('http://localhost', $this->object->getUri());
    }

    public function testSetEmail()
    {
        $this->object->setEmail('john@localhost');
        $this->assertEquals('john@localhost', $this->object->getEmail());
    }
}
