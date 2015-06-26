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

$response = $client->getResponse('http://php.net/feed.atom', new \DateTime());

echo $response->getBody();
