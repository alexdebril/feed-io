# feed-io

[![Latest Stable Version](https://poser.pugx.org/debril/feed-io/v/stable.png)](https://packagist.org/packages/debril/feed-io)
[![Build Status](https://github.com/alexdebril/feed-io/actions/workflows/ci.yml/badge.svg)](https://github.com/alexdebril/feed-io/actions/workflows/ci.yml/)
[![Maintainability](https://api.codeclimate.com/v1/badges/c418d2c84346aa398d19/maintainability)](https://codeclimate.com/github/alexdebril/feed-io/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/c418d2c84346aa398d19/test_coverage)](https://codeclimate.com/github/alexdebril/feed-io/test_coverage)

[feed-io](https://github.com/alexdebril/feed-io) is a PHP library built to consume and serve news feeds. It features:

- JSONFeed / Atom / RSS read and write support
- Feeds auto-discovery through HTML headers
- a Command line interface to discover and read feeds
- PSR-7 Response generation with accurate cache headers
- HTTP Headers support when reading feeds in order to save network traffic
- Detection of the format (RSS / Atom) when reading feeds
- Enclosure support to handle external medias like audio content
- Feed logo support (RSS + Atom)
- PSR compliant logging
- Content filtering to fetch only the newest items
- DateTime detection and conversion
- A generic HTTP ClientInterface
- Integrates with every [PSR-18 compatible HTTP client](https://www.php-fig.org/psr/psr-18/).

This library is highly extensible and is designed to adapt to many situations, so if you don't find a solution through the documentation feel free to ask in the [discussions](https://github.com/alexdebril/feed-io/discussions).

# Installation

Use Composer to add feed-io into your project's requirements :

```sh
    composer require debril/feed-io
 ```

# Requirements

| feed-io | PHP  |
|---------|------|
| 4.x     | 7.1+ |
| 5.0     | 8.0+ |
| 6.0     | 8.1+ |

feed-io 4 requires PHP 7.1+, feed-io 5 requires PHP 8.0+. All versions relies on `psr/log` and any PSR-18 compliant HTTP client. To continue using you may require `php-http/guzzle7-adapter`. it suggests `monolog` for logging. Monolog is not the only library suitable to handle feed-io's logs, you can use any PSR/Log compliant library instead.

# Usage

## CLI

Let's suppose you installed feed-io using Composer, you can use its command line client to read feeds from your terminal :

```shell
./vendor/bin/feedio read http://php.net/feed.atom
```

## reading

feed-io is designed to read feeds across the internet and to publish your own. Its main class is [FeedIo](https://github.com/alexdebril/feed-io/blob/master/src/FeedIo/FeedIo.php) :

```php

// create a simple FeedIo instance, e.g. with the Symfony HTTP Client
$client = new \FeedIo\Adapter\Http\Client(new Symfony\Component\HttpClient\HttplugClient());
$feedIo = \FeedIo\FeedIo($client);

// read a feed
$result = $feedIo->read($url);

// get title
$feedTitle = $result->getFeed()->getTitle();

// iterate through items
foreach( $result->getFeed() as $item ) {
    echo $item->getTitle();
}

```

If you need to get only the new items since the last time you've consumed the feed, use the result's `getItemsSince()` method:

```php
// read a feed and specify the `$modifiedSince` limit to fetch only items newer than this date
$result = $feedIo->read($url, $feed, $modifiedSince);

// iterate through new items
foreach( $result->getItemsSince() as $item ) {
    echo $item->getTitle();
}

```

You can also mix several filters to exclude items according to your needs:

```php
// read a feed
$result = $feedIo->read($url, $feed, $modifiedSince);

// remove items older than `$modifiedSince`
$since = new FeedIo\Filter\Since($result->getModifiedSince());

// Your own filter
$database = new Acme\Filter\Database();

$chain = new Chain();
$chain
    ->add($since)
    ->add($database);

// iterate through new items
foreach( $result->getFilteredItems($chain) as $item ) {
    echo $item->getTitle();
}

```

In order to save bandwidth, feed-io estimates the next time it will be relevant to read the feed and get new items from it.

```php
$nextUpdate = $result->getNextUpdate();
echo "computed next update: {$nextUpdate->format(\DATE_ATOM)}";

// you may need to access the statistics
$updateStats = $result->getUpdateStats();
echo "average interval in seconds: {$updateStats->getAverageInterval()}";
```

feed-io calculates the next update time by first detecting if the feed was active in the last 7 days and if not we consider it as sleepy. The next update date for a sleepy feed is set to the next day at the same time. If the feed isn't sleepy we use the average interval and the median interval by adding those intervals to the feed's last modified date and compare the result to the current time. If the result is in the future, then it's returned as the next update time. If none of them are in the future, we considered the feed will be updated quite soon, so the next update time is one hour later from the moment of the calculation.

Please note: the fixed delays for sleepy and closed to be updated feeds can be set through `Result::getNextUpdate()` arguments, see [Result](src/FeedIo/Reader/Result.php) for more details.

## Feeds discovery

A web page can refer to one or more feeds in its headers, feed-io provides a way to discover them :

```php

// create a simple FeedIo instance, e.g. with the Symfony HTTP Client
$client = new \FeedIo\Adapter\Http\Client(new Symfony\Component\HttpClient\HttplugClient());
$feedIo = \FeedIo\FeedIo($client);

$feeds = $feedIo->discover($url);

foreach( $feeds as $feed ) {
    echo "discovered feed : {$feed}";
}

```
Or you can use feed-io's command line :

```shell
./vendor/bin/feedio discover https://a-website.org
```

You'll get all discovered feeds in the output.

## formatting an object into a XML stream

```php

// build the feed
$feed = new FeedIo\Feed;
$feed->setTitle('...');

// convert it into Atom
$atomString = $feedIo->toAtom($feed);

// or ...
$atomString = $feedIo->format($feed, 'atom');

```

## Adding a StyleSheet

```php

$feed = new FeedIo\Feed;
$feed->setTitle('...');
$styleSheet = new StyleSheet('http://url-of-the-xsl-stylesheet.xsl');
$feed->setStyleSheet($styleSheet);

```

## building a feed including medias

```php
// build the feed
$feed = new FeedIo\Feed;
$feed->setTitle('...');

$item = $feed->newItem();

// add namespaces
$feed->setNS(
    'itunes', //namespace
    'http://www.itunes.com/dtds/podcast-1.0.dtd' //dtd for the namespace
        );
$feed->set('itunes,title', 'Sample Title'); //OR any other element defined in the namespace.
$item->addElement('itunes:category', 'Education');

// build the media
$media = new \FeedIo\Feed\Item\Media
$media->setUrl('http://yourdomain.tld/medias/some-podcast.mp3');
$media->setType('audio/mpeg');

// add it to the item
$item->addMedia($media);

$feed->add($item);

```

## Creating a valid PSR-7 Response with a feed

You can turn a `\FeedIo\FeedInstance` directly into a PSR-7 valid response using `\FeedIo\FeedIo::getPsrResponse()` :

```php

$feed = new \FeedIo\Feed;

// feed the beast ...
$item = new \FeedIo\Feed\Item;
$item->set ...
$feed->add($item);

$atomResponse = $feedIo->getPsrResponse($feed, 'atom');

$jsonResponse = $feedIo->getPsrResponse($feed, 'json');

```

## Building a FeedIo instance

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

Another example with Monolog configured to write on the standard output :

```php
// create a simple FeedIo instance, e.g. with the Symfony HTTP Client
$client = new \FeedIo\Adapter\Http\Client(new Symfony\Component\HttpClient\HttplugClient());
$logger = new Monolog\Logger('default', [new Monolog\Handler\StreamHandler('php://stdout')]);
$feedIo = \FeedIo\FeedIo($client, $logger);

```

### Inject a custom Logger

You can inject any Logger you want as long as it implements `Psr\Log\LoggerInterface`. Monolog does, but it's not the only library : https://packagist.org/providers/psr/log-implementation

```php
use FeedIo\FeedIo;
use FeedIo\Adapter\Guzzle\Client;
use GuzzleHttp\Client as GuzzleClient;
use Custom\Logger;

$client = new Client(new GuzzleClient());
$logger = new Logger();

$feedIo = new FeedIo($client, $logger);

```

### Inject a custom HTTP Client

Since 6.0 there is a generic HTTP adapter that wraps any PST-18 compliant HTTP client. 

```php
use CustomPsr18\Client as CustomClient;

$client = new Custom\Adapter\Http\Client(new CustomClient())
$logger = new Psr\Log\NullLogger();

$feedIo = new FeedIo\FeedIo($client, $logger);

```

## Configure feed-io using the Factory

The factory has been deprecated in feed-io 5.2 and was removed in 6.0. Instantiate the facade directly and pass in the desired HTTP client and logger interface.

## Dealing with missing timezones

Sometimes you have to consume feeds in which the timezone is missing from the dates. In some use-cases, you may need to specify the feed's timezone to get an accurate value, so feed-io offers a workaround for that :

```php
$feedIo->getDateTimeBuilder()->setFeedTimezone(new \DateTimeZone($feedTimezone));
$result = $feedIo->read($feedUrl);
$feedIo->getDateTimeBuilder()->resetFeedTimezone();
```

Don't forget to reset `feedTimezone` after fetching the result, or you'll end up with all feeds located in the same timezone.

## Built with PHP Storm

Most of feed-io's code was written using [PHP Storm](https://www.jetbrains.com/phpstorm/) courtesy of Jetbrains.
