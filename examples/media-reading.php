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

$client = new \FeedIo\Adapter\FileSystem\Client();

$logger = new \Psr\Log\NullLogger();

$feedIo = new \FeedIo\FeedIo($client, $logger);

$result = $feedIo->read(dirname(__FILE__).'/../tests/samples/enclosure-atom.xml');

$feed = $result->getFeed();

foreach($feed as $item) {
    foreach($item->getMedias() as $media) {
        var_dump($media);
    }
}
