# UPGRADE FROM 5.x to 6.0

Several major changes in version 6.0:
 - Requires PHP 8.1
 - The factory has been removed. Use `new` to construct your FeedIO instance: `new \FeedIo\FeedIo($client, $logger)`
 - Feed IO comes no longer bundled with a default HTTP client, but uses HTTPlug instead. To continue using Guzzle, please require `php-http/guzzle7-adapter`.
 - Feed IO does no longer set a custom user agent. However, HTTP clients usually add a default themselves. If the feed you want to read requires a specific user agent, please configure your HTTP client accordingly, before you inject it into Feed IO. 
