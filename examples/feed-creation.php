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

use \FeedIo\Factory;
use \FeedIo\Feed;

$feed = new Feed();
$feed->setLink('https://feed-io.net');
$feed->setTitle('feed-io example feed');
$feed->setDescription('my greate feed');

// The item instance SHOULD be instanciated by the feed
$item = $feed->newItem();

$item->setTitle('a title');
$item->setLastModified(new \DateTime());
$item->setLink('https://feed-io.net/item/1');
$item->setContent("Hope you like the code you are reading");
$item->setSummary('my summary');
$feed->add($item);

$feedIo = Factory::create()->getFeedIo();

echo 'ATOM' . PHP_EOL;
echo $feedIo->format($feed, 'atom');
echo PHP_EOL;

echo 'RSS' . PHP_EOL;
echo $feedIo->format($feed, 'rss');
echo PHP_EOL;

echo 'JSON Feed' . PHP_EOL;
echo $feedIo->format($feed, 'json');
echo PHP_EOL;