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

echo 'see https://github.com/alexdebril/feed-io/issues/134 for full explanations' . PHP_EOL;

$dateTimeBuilder = new \FeedIo\Rule\DateTimeBuilder();
$pubDate = 'Wed, 02 Aug 2017 07:21:29';

echo 'the publish date\'s timezone is America/chicago but $dateTimeBuilder ignores it. The date below is wrong'  . PHP_EOL;

$dateTime = $dateTimeBuilder->convertToDateTime($pubDate);

var_dump($dateTime);
echo "timestamp : {$dateTime->getTimestamp()}" . PHP_EOL;

echo 'after setting the feed\'s timezone in the builder\'s attributes, the date is right' . PHP_EOL;

$dateTimeBuilder->setFeedTimezone(new \DateTimeZone('America/Chicago'));

$dateTime = $dateTimeBuilder->convertToDateTime($pubDate);

var_dump($dateTime);
echo "timestamp : {$dateTime->getTimestamp()}" . PHP_EOL;

echo 'if I really want the date in its original timezone, I can set it' . PHP_EOL;

$dateTime->setTimezone(new \DateTimeZone('America/Chicago'));

var_dump($dateTime);
echo "timestamp : {$dateTime->getTimestamp()}" . PHP_EOL;
