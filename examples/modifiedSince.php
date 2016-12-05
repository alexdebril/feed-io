<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.DIRECTORY_SEPARATOR.'bootstrap.php';

$client = new \FeedIo\Adapter\Guzzle\Client(new GuzzleHttp\Client());

$logger = new \Psr\Log\NullLogger();

$feedIo = new \FeedIo\FeedIo($client, $logger);

$result = $feedIo->readSince('http://php.net/feed.atom', new \DateTime('-1 month'));

echo "feed title : {$result->getFeed()->getTitle()} \n ";

$newItems = $result->getFeed();
foreach($newItems as $value) {
    echo $value->getTitle()  . ' : ' .  $value->getLastModified()->format(\DateTime::ATOM) . PHP_EOL;
}
