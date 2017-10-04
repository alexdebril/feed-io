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

$feed = new \FeedIo\Feed();

// ask for a new Item
$item = $feed->newItem();

// build the media
$media = new \FeedIo\Feed\Item\Media();
$media->setUrl('http://yourdomain.tld/medias/some-podcast.mp3');
$media->setType('audio/mpeg');

// add it to the item
$item->addMedia($media);
$item->setLink('http://yourdomain.tld/item/1');

// add the item to the feed
$feed->add($item);

$client = new \FeedIo\Adapter\NullClient();

$logger = new \Psr\Log\NullLogger();

$feedIo = new \FeedIo\FeedIo($client, $logger);
echo $feedIo->toAtom($feed);
