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

$feedIo = \FeedIo\Factory::create()->getFeedIo();

$feedIo->getDateTimeBuilder()->setFeedTimezone(new \DateTimeZone('-0500'));
$result = $feedIo->read('http://news.php.net/group.php?group=php.announce&format=rss');

echo "First item's pubDate raw value in the feed"  .PHP_EOL;

$domItems = $result->getDocument()->getDOMDocument()->getElementsByTagName('item');

/** @var \DOMElement $firstDomItem */
$firstDomItem = $domItems->item(0);
$pubDate = $firstDomItem->getElementsByTagName('pubDate')->item(0);

var_dump($pubDate->nodeValue);

$pubDateTime = new \DateTime($pubDate->nodeValue);

echo "here is its timestamp : {$pubDateTime->getTimestamp()}" . PHP_EOL;

$feed = $result->getFeed();

/** @var \FeedIo\Feed\ItemInterface $item */
$feed->rewind();
$item = $feed->current();

echo "var_dump the first item's pubDate after parsing. It's the same date converted in your local configuration's timezone" . PHP_EOL;
var_dump($item->getLastModified());
echo "here is its timestamp : {$item->getLastModified()->getTimestamp()}" . PHP_EOL;

if($pubDateTime->getTimestamp() === $item->getLastModified()->getTimestamp()) {
    echo "HOURAY, both timestamps match !" . PHP_EOL;
}
