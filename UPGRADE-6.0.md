# UPGRADE FROM 5.x to 6.0

Several major changes in version 6.0:
 - Requires PHP 8.1
 - The factory has been removed. Use `new` to construct your FeedIO instance: `new \FeedIo\FeedIo($client, $logger)`
 - Guzzle comes no longer bundled with a default HTTP Client, but uses HTTPlug instead. To continue using Guzzle, please require `php-http/guzzle7-adapter`.
