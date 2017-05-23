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

$result = $feedIo->read('https://debril.org/feed/');

echo "feed title : {$result->getFeed()->getTitle()} \n ";

foreach ($result->getFeed() as $item) {
    echo "item title : {$item->getTitle()} \n ";

    foreach ($item->getAllElements() as $element) {
        echo "element name : " . $element->getName() . PHP_EOL;
        
        foreach( $element->getAllElements() as $subElement) {
            echo "sub element name : " . $subElement->getName() . PHP_EOL;
            echo "sub element value : " . $subElement->getValue() . PHP_EOL;
        }
    }

}
