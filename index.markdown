---
layout: home
---



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

