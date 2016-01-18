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

$result = $feedIo->read('http://php.net/feed.atom');

echo "feed title : {$result->getFeed()->getTitle()} \n ";

foreach ($result->getFeed() as $item) {
    echo "item title : {$item->getTitle()} \n ";

    // let's turn php.net into a PodCast
    $media = new \FeedIo\Feed\Item\Media();
    $media->setUrl('http://yourdomain.tld/medias/some-podcast.mp3');
    $media->setType('audio/mpeg');

    // add it to the item
    $item->addMedia($media);
}

$domDocument = $feedIo->toAtom($result->getFeed());
echo $domDocument->saveXML();
