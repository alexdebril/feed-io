# feed-io

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9cabcb4b-695e-43fa-8b83-a1f9ecefea88/big.png)](https://insight.sensiolabs.com/projects/9cabcb4b-695e-43fa-8b83-a1f9ecefea88)

[![Latest Stable Version](https://poser.pugx.org/debril/feed-io/v/stable.png)](https://packagist.org/packages/debril/feed-io)
[![Build Status](https://secure.travis-ci.org/alexdebril/feed-io.png?branch=master)](http://travis-ci.org/alexdebril/feed-io)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexdebril/feed-io/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexdebril/feed-io/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/alexdebril/feed-io/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alexdebril/feed-io/?branch=master)


[feed-io](https://github.com/alexdebril/feed-io) is a PHP library built to consume and serve RSS / Atom feeds. It features:

- Atom / RSS read and write support
- HTTP Headers support when reading feeds in order to save network traffic
- Detection of the format (RSS / Atom) when reading feeds
- PSR compliant logging
- Content filtering to fetch only the newest items
- DateTime detection and conversion
- A generic HTTP ClientInterface
- Guzzle Client integration

Keep informed about new releases and incoming features : http://blog.debril.fr/category/feed-io

# Installation

Edit composer.json and add the following line in the "require" section:

    "debril/feed-io": "dev-master"

then, ask Composer to install it:

    composer.phar update debril/feed-io
    
# Requirements

feed-io requires : 

- php 5.4+
- psr/log 1.0

it suggests : 
- guzzlehttp/guzzle 4.1+
- monolog/monolog 1.10+

# Fetching the repository

Do this if you want to contribute (and you're welcome to do so):

    git clone https://github.com/alexdebril/feed-gio.git

    composer.phar install

# Unit Testing

You can run the unit test suites using the following command in the library's source directory:

```sh

make test

```

Usage
=====

feed-io is designed to read feeds across the internet and to publish your own. Its main class is [FeedIo](https://github.com/alexdebril/feed-io/blob/master/src/FeedIo/FeedIo.php) :

## reading

```php

// first dependency : the HTTP client
// here we use Guzzle as a dependency for the client
$guzzle = new GuzzleHttp\Client();
$client = new FeedIo\Adapter\Guzzle\Client($guzzle);

// second dependency : a PSR-3 logger
$logger = new Psr\Log\NullLogger();

// now create FeedIo's instance
$feedIo = new FeedIo\FeedIo($client, $logger);

// read a feed
$result = $feedIo->read($url);

// or read a feed since a certain date
$result = $feedIo->readSince($url, new \DateTime('-7 days'));

// get title
$feedTitle = $result->getFeed()->getTitle();

// iterate through items
foreach( $result->getFeed() as $item ) {
    $item->getTitle();
}

```

## formatting an object into a XML stream

```php

// build the feed
$feed = new FeedIo\Feed;
$feed->setTitle('...');

// convert it into Atom
$dom = $feedIo->toAtom($feed);

// or ...
$dom = $feedIo->format($feed, 'atom');

```

