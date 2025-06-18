# feed-io

[feed-io](https://github.com/php-feed-io/feed-io) is a PHP library built to consume and serve RSS / Atom feeds.

```php
<?php
require 'vendor/autoload.php';

use \FeedIo\Factory;

$feedIo = Factory::create()->getFeedIo();
$result = $feedIo->read('http://php.net/feed.atom');

echo "feed title : {$result->getFeed()->getTitle()} \n";
foreach ($result->getFeed() as $item) {
    echo "{$item->getLastModified()->format(\DateTime::ATOM)} : {$item->getTitle()} \n";
    echo "{$item->getDescription()} \n";
}
```

# Installation

Use Composer to add feed-io into your project's requirements :

```bash
composer require php-feed-io/feed-io
```

# Requirements

| feed-io |	PHP  |
| ------- | ---- |
| 5.0     |	8.0+ |
| 6.0     | 8.1+ |

# Usage
## CLI

Let's suppose you installed feed-io using Composer, you can use its command line client to read feeds from your terminal :

```bash 
./vendor/bin/feedio read http://php.net/feed.atom
```

You can specify the number of items you want to read using the --count option. The instruction below will display the latest item :

```bash
./vendor/bin/feedio read -c 1 http://php.net/feed.atom
```

## Reading

feed-io is designed to read feeds across the internet and to publish your own. Its main class is FeedIo :

```php
<?php
// create a simple FeedIo instance
$feedIo = \FeedIo\Factory::create()->getFeedIo();

// read a feed
$result = $feedIo->read($url);

// get title
$feedTitle = $result->getFeed()->getTitle();

// iterate through items
foreach( $result->getFeed() as $item ) {
    echo $item->getTitle();
}
              
```
       
## Next Update estimation

In order to save bandwidth, feed-io estimates the next time it will be relevant to read the feed and get new items from it.

```php
<?php
$nextUpdate = $result->getNextUpdate();
echo "computed next update: {$nextUpdate->format(\DATE_ATOM)}";

// you may need to access the statistics
$updateStats = $result->getUpdateStats();
echo "average interval in seconds: {$updateStats->getAverageInterval()}";
```


feed-io calculates the next update time by first detecting if the feed was active in the last 7 days and if not we consider it as sleepy. The next update date for a sleepy feed is set to the next day at the same time. If the feed isn't sleepy we use the average interval and the median interval by adding those intervals to the feed's last modified date and compare the result to the current time. If the result is in the future, then it's returned as the next update time. If none of them are in the future, we considered the feed will be updated quite soon, so the next update time is one hour later from the moment of the calculation. Please note: the fixed delays for sleepy and closed to be updated feeds can be set through Result::getNextUpdate() arguments, see Result for more details.

## Formatting an object into a XML stream

```php            
<?php
// build the feed
$feed = new FeedIo\Feed;
$feed->setTitle('...');

// convert it into Atom
$atomString = $feedIo->toAtom($feed);

// or ...
$atomString = $feedIo->format($feed, 'atom');
```

### Building a feed including medias

```php
// build the feed
$feed = new FeedIo\Feed;
$feed->setTitle('...');

$item = $feed->newItem();

// build the media
$media = new \FeedIo\Feed\Item\Media
$media->setUrl('http://yourdomain.tld/medias/some-podcast.mp3');
$media->setType('audio/mpeg');

// add it to the item
$item->addMedia($media);

$feed->add($item);
```

## Activate logging

feed-io natively supports PSR-3 logging, you can activate it by choosing a 'builder' in the factory :

```php
$feedIo = \FeedIo\Factory::create(['builder' => 'monolog'])->getFeedIo();
```

feed-io only provides a builder to create Monolog\Logger instances. You can write your own, as long as the Builder implements BuilderInterface.
Building a FeedIo instance without the factory

To create a new FeedIo instance you only need to inject two dependencies :

- an HTTP Client implementing FeedIo\Adapter\ClientInterface. It can be wrapper for an external library like FeedIo\Adapter\Guzzle\Client
- a PSR-3 logger implementing Psr\Log\LoggerInterface

```php
// first dependency : the HTTP client
// here we use Guzzle as a dependency for the client
$guzzle = new GuzzleHttp\Client();
// Guzzle is wrapped in this adapter which is a FeedIo\Adapter\ClientInterface  implementation
$client = new FeedIo\Adapter\Guzzle\Client($guzzle);

// second dependency : a PSR-3 logger
$logger = new Psr\Log\NullLogger();

// now create FeedIo's instance
$feedIo = new FeedIo\FeedIo($client, $logger);
```

