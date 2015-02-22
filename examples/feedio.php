<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/02/15
 * Time: 23:18
 */

require __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$client = new \FeedIo\Adapter\Guzzle\Client(new GuzzleHttp\Client());

$logger = new \Psr\Log\NullLogger();

$feedIo = new \FeedIo\FeedIo($client, $logger);

$result = $feedIo->read('http://php.net/feed.atom');

echo "feed title : {$result->getFeed()->getTitle()} \n " ;

foreach( $result->getFeed() as $item ) {
    echo "item title : {$item->getTitle()} \n ";
}

$domDocument = $feedIo->toAtom($result->getFeed());
