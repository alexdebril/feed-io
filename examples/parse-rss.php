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

// new DateTimeBuilder : it will be used by the parser to convert formatted dates into DateTime instances
$dateTimeBuilder = new \FeedIo\Rule\DateTimeBuilder();

// new Standard\\Rss : it will provide all standard specific rules to the parser
$standard = new \FeedIo\Standard\Rss($dateTimeBuilder);

// new Parser: it will turn a RSS stream into a Feed instance, using the rules provided by the Standard
// the Logger must implement the PSR3 logging standard
$parser = new \FeedIo\Parser($standard, new \Psr\Log\NullLogger());

// the file is sample-rss.xml
$file = dirname(__FILE__)."/../tests/samples/rss/sample-rss.xml";

// we load it using the Dom library
$document = new DOMDocument();
$document->load($file, LIBXML_NOBLANKS | LIBXML_COMPACT);

// Now let's parse it
// The second argument must implement FeedInterface
$feed = $parser->parse($document, new \FeedIo\Feed());

// $feed is now ready
echo "feed's title : {$feed->getTitle()} \n";

// FeedInterface extends \Iterator, we can iterate through it
foreach ($feed as $item) {
    echo "item's title : {$item->getTitle()} \n";
}
