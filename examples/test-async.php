<?php

require __DIR__.DIRECTORY_SEPARATOR.'bootstrap.php';

$requests = [
    new FeedIo\Async\Request('https://jsonfeed.org/feed.json'),
    new FeedIo\Async\Request('https://jsonfeed.org/xml/rss.xml'),
    new FeedIo\Async\Request('https://packagist.org/feeds/releases.rss'),
    new FeedIo\Async\Request('https://packagist.org/feeds/packages.rss'),
    new FeedIo\Async\Request('https://debril.org/feed/'),
    new FeedIo\Async\Request('https://localhost:8000'),
];
$logger = (new FeedIo\Factory\Builder\MonologBuilder())->getLogger();

$feedIo = new \FeedIo\FeedIo(new \FeedIo\Adapter\Guzzle\Client(new \GuzzleHttp\Client()), $logger);

$feedIo->readAsync($requests, new \FeedIo\Async\DefaultCallback($logger));
