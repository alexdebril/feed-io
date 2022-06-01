<?php

namespace FeedIo\Filter;

use FeedIo\Feed\Item;
use PHPUnit\Framework\TestCase;

class SinceTest extends TestCase
{
    public function testFilter()
    {
        $filter = new Since(new \DateTime('-1 day'));
        $this->assertTrue($filter->filter((new Item())->setLastModified(new \DateTime('-1 hour'))));
        $this->assertTrue($filter->filter((new Item())->setLastModified(new \DateTime('-1 day'))));
        $this->assertFalse($filter->filter((new Item())->setLastModified(new \DateTime('-2 day'))));
    }
}
