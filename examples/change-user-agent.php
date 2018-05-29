<?php

require __DIR__.DIRECTORY_SEPARATOR.'bootstrap.php';

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use Monolog\Logger;

$logger = new Logger('Logger');
$stack = HandlerStack::create();
$stack->push(
    Middleware::log(
        $logger,
        new MessageFormatter('{request}')
    )
);

$client = new \FeedIo\Adapter\Guzzle\Client(
    new GuzzleHttp\Client([
        'handler' => $stack
        ]),
    'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36'
);

$feedIo = new \FeedIo\FeedIo($client, $logger);

$result = $feedIo->read('http://php.net/feed.atom');

echo "feed title : {$result->getFeed()->getTitle()} \n ";

$client->setUserAgent('Another User Agent');

$feedIo->read('http://php.net/feed.atom');

echo "feed title : {$result->getFeed()->getTitle()} \n ";
