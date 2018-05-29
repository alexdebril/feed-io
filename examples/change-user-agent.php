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
        ])
);

$feedIo = new \FeedIo\FeedIo($client, $logger);

$result = $feedIo->read('http://php.net/feed.atom');

echo "feed title : {$result->getFeed()->getTitle()} \n ";

$client->setUserAgent('Another User Agent');

$feedIo->read('http://php.net/feed.atom');

echo "feed title : {$result->getFeed()->getTitle()} \n ";
