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

$items = [
    getItem('item 1', 'Lorem Ipsum'),
    getItem('item 2', '<p>Foo Bar</p>'),
];

$feed = new \FeedIo\Feed();
$feed->setTitle('feed title');

foreach($items as $item) {
    $feed->add($item);
}

echo $feedIo->format($feed, 'json');

function getItem($title, $description)
{
    $item = new \FeedIo\Feed\Item();
    $item->setTitle($title);
    $item->setDescription($description);
    $media = new \FeedIo\Feed\Item\Media();
    $media->setUrl('http://localhost/some-resource.jpg');
    $media->setType('image/jpeg');
    $item->addMedia($media);

    return $item;
}
