<?php

require __DIR__.DIRECTORY_SEPARATOR.'bootstrap.php';

$requests = [
    new FeedIo\Async\Request('https://jsonfeed.org/feed.json'),
    new FeedIo\Async\Request('https://jsonfeed.org/xml/rss.xml'),
    new FeedIo\Async\Request('https://packagist.org/feeds/releases.rss'),
    new FeedIo\Async\Request('https://packagist.org/feeds/packages.rss'),
    new FeedIo\Async\Request('https://debril.org/feed/'),
];

$reader = new \FeedIo\Async\Reader(
    new \FeedIo\Reader(new \FeedIo\Adapter\NullClient(), new \Psr\Log\NullLogger()),
    new \FeedIo\Async\DefaultCallback(),
    '\FeedIo\Feed'
);

$reader->process($requests);
