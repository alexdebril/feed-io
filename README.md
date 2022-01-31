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
- DateTime detection and conversion
- A generic HTTP ClientInterface
- Guzzle Client integration

This library is highly extensible and is designed to adapt to many situations, so if you don't find a solution through the documentation feel free to ask in the [discussions](https://github.com/alexdebril/feed-io/discussions).

# Installation

Use Composer to add feed-io into your project's requirements :

```sh
    composer require debril/feed-io
 ```

# Requirements

| feed-io | PHP  |
| --------| ---- |
|   4.x   | 7.1+ |
|   5.0   | 8.0+ |

feed-io 4 requires PHP 7.1+, feed-io 5 requires PHP 8.0+. All versions relies on `psr/log` and `guzzle`. it suggests `monolog` for logging. Monolog is not the only library suitable to handle feed-io's logs, you can use any PSR/Log compliant library instead.

# Usage

## CLI

Let's suppose you installed feed-io using Composer, you can use its command line client to read feeds from your terminal :

```shell
./vendor/bin/feedio read http://php.net/feed.atom
```

You can specify the number of items you want to read using the --count option. The instruction below will display the latest item :

```shell
./vendor/bin/feedio read -c 1 http://php.net/feed.atom
```

## reading

feed-io is designed to read feeds across the internet and to publish your own. Its main class is [FeedIo](https://github.com/alexdebril/feed-io/blob/master/src/FeedIo/FeedIo.php) :

```php

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

$feedIo = \FeedIo\Factory::create()->getFeedIo();

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

## Configure feed-io using the Factory

We saw in the [reading section](#reading) that to get a simple `FeedIo` instance we can simply call the `Factory` statically and let it return a fresh `FeedIo` composed of the main dependencies it needs to work. The problem is that we may want to inject configuration to its underlying components, such as configuring Guzzle to ignore SSL errors.

For that, we will inject the configuration through `Factory::create()` parameters, first one being for the logging system, and the second one for the HTTP Client (well, Guzzle).

### Configure Guzzle through the Factory

A few lines above, we talked about ignoring ssl errors, let's see how to configure Guzzle to do this:

```php
$feedIo = \FeedIo\Factory::create(
        ['builder' => 'NullLogger'], // assuming you want feed-io to keep quiet
        ['builder' => 'GuzzleClient', 'config' => ['verify' => false]]
    )->getFeedIo();
```

It's important to specify the "builder", as it's the class that will be in charge of actually building the instance.

### activate logging

feed-io natively supports PSR-3 logging, you can activate it by choosing a 'builder' in the factory :

```php

$feedIo = \FeedIo\Factory::create(['builder' => 'monolog'])->getFeedIo();

```

feed-io only provides a builder to create Monolog\Logger instances. You can write your own, as long as the Builder implements BuilderInterface.

## Building a FeedIo instance without the factory

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
use FeedIo\FeedIo;
use FeedIo\Adapter\Guzzle\Client;
use GuzzleHttp\Client as GuzzleClient;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$client = new Client(new GuzzleClient());
$logger = new Logger('default', [new StreamHandler('php://stdout')]);

$feedIo = new FeedIo($client, $logger);

```

### Guzzle Configuration

You can configure Guzzle before injecting it to `FeedIo` :

```php
use FeedIo\FeedIo;
use FeedIo\Adapter\Guzzle\Client;
use GuzzleHttp\Client as GuzzleClient;
use \Psr\Log\NullLogger;

// We want to timeout after 3 seconds
$guzzle = new GuzzleClient(['timeout' => 3]);
$client = new Client($guzzle);

$logger = new NullLogger();

$feedIo = new \FeedIo\FeedIo($client, $logger);

```
Please read [Guzzle's documentation](http://docs.guzzlephp.org/en/stable/index.html) to get more information about its configuration.

#### Caching Middleware usage

To prevent your application from hitting the same feeds multiple times, you can inject [Kevin Rob's cache middleware](https://github.com/Kevinrob/guzzle-cache-middleware) into Guzzle's instance :

```php
use FeedIo\FeedIo;
use FeedIo\Adapter\Guzzle\Client;
use GuzzleHttp\Client As GuzzleClient;
use GuzzleHttp\HandlerStack;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Psr\Log\NullLogger;

// Create default HandlerStack
$stack = HandlerStack::create();

// Add this middleware to the top with `push`
$stack->push(new CacheMiddleware(), 'cache');

// Initialize the client with the handler option
$guzzle = new GuzzleClient(['handler' => $stack]);
$client = new Client($guzzle);
$logger = new NullLogger();

$feedIo = new \FeedIo\FeedIo($client, $logger);

```

As feeds' content may vary often, caching may result in unwanted behaviors.

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

Warning : it is highly recommended to use the default Guzzle Client integration.

If you really want to use another library to read feeds, you need to create your own `FeedIo\Adapter\ClientInterface` class to embed interactions with the library :

```php
use FeedIo\FeedIo;
use Custom\Adapter\Client;
use Library\Client as LibraryClient;
use Psr\Log\NullLogger;

$client = new Client(new LibraryClient());
$logger = new NullLogger();

$feedIo = new FeedIo($client, $logger);

```

### Factory or Dependency Injection ?

Choosing between using the Factory or build `FeedIo` without it is a question you must ask yourself at some point of your project. The Factory is mainly designed to let you use feed-io with the lesser efforts and get your first results in a small amount of time. However, it doesn't let you benefit of all Monolog's and Guzzle's features, which could be annoying. Dependency injection will also let you choose another library to handle logs if you need to.

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
