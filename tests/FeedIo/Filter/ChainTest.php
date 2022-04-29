<?php

namespace FeedIo\Filter;

use FeedIo\Feed;
use FeedIo\Feed\Item;
use PHPUnit\Framework\TestCase;

class ChainTest extends TestCase
{
    public function testFilter()
    {
        $chain = new Chain();
        $chain->add(new Since(new \DateTime('-1 day')));

        $feed = new Feed();
        $feed->add((new Item())->setLastModified(new \DateTime('-1 hour')));
        $feed->add((new Item())->setLastModified(new \DateTime('-1 day')));
        $feed->add((new Item())->setLastModified(new \DateTime('-1 month')));

        $filtered = $chain->filter($feed);
        $this->assertEquals(2, iterator_count($filtered));
    }

    public function testFancyFilter()
    {
        $chain = new Chain();
        $fancyFilter = $this->getMockForAbstractClass('\FeedIo\Filter\FilterInterface');
        $fancyFilter->expects($this->exactly(2))->method('filter')->will($this->returnCallback(function (Item $item) {
            return $item->getTitle() === '1 day ago';
        }));

        $chain->add(new Since(new \DateTime('-1 day')));
        $chain->add($fancyFilter);

        $feed = new Feed();
        $feed->add((new Item())->setTitle('1 hour ago')->setLastModified(new \DateTime('-1 hour')));
        $feed->add((new Item())->setTitle('1 day ago')->setLastModified(new \DateTime('-1 day')));
        $feed->add((new Item())->setTitle('1 month ago')->setLastModified(new \DateTime('-1 month')));

        $filtered = $chain->filter($feed);
        $count = 0;
        foreach ($filtered as $item) {
            $count++;
        }
        $this->assertEquals(1, $count);
        $this->assertEquals('1 day ago', $item->getTitle());
    }
}
