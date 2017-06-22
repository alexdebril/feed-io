# UPGRADE FROM 2.x to 3.0

## FeedIo::format() now returns a string

In version 2.x FeedIo::format() returned a DomDocument. Now that feed-io supports JSON Feed, this is no longer relevant so now FeedIo::format() returns a string

Before :

```php
// Feed creation
$feed = new \FeedIo\Feed();

// set all feed properties and add items ...
$feed->setTitle('your title');

// ...


$feedIo = \FeedIo\Factory::create()->getFeedIo();

$domDocument = $feedIo->format($feed, 'atom');
echo $domDocument->saveXML();

```

Now you get the string :


```php
// Feed creation
$feed = new \FeedIo\Feed();

$feedIo = \FeedIo\Factory::create()->getFeedIo();

echo $feedIo->format($feed, 'atom');

```

## Result::getDocument() returns a \FeedIo\Reader\Document instance

Instead of a DomDocument.

Before :

```php
$feedIo = \FeedIo\Factory::create()->getFeedIo();

$result = $feedIo->read('http://php.net/feed.atom');

$dom = $result->getDocument();

```

After :

```php
$feedIo = \FeedIo\Factory::create()->getFeedIo();

$result = $feedIo->read('http://php.net/feed.atom');

$dom = $result->getDocument()->getDOMDocument();

```

This is because Result::getDocument()'s return value is a wrapper for both XML and JSON streams.

### That's it

There are no other modifications to upgrade into 3.0.
