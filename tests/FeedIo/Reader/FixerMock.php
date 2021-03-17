<?php

namespace FeedIo\Reader;

class FixerMock extends FixerAbstract
{
    public function correct(Result $result) : FixerAbstract
    {
        $feed = $result->getFeed();
        $feed->setTitle('corrected');

        return $this;
    }
}
