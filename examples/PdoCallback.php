<?php

require __DIR__.DIRECTORY_SEPARATOR.'bootstrap.php';

use \Psr\Log\LoggerInterface;

class PdoCallback implements \FeedIo\Async\CallbackInterface
{

    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * PdoCallback constructor.
     * @param $pdo
     * @param $logger
     */
    public function __construct(\PDO $pdo, LoggerInterface $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param \FeedIo\Reader\Result $result
     */
    public function process(\FeedIo\Reader\Result $result): void
    {
        $feed = $result->getFeed();

        $this->getLogger()->info("Received : {$feed->getTitle()} - storing it");

        $this->persistFeed($feed);
        foreach($feed as $item) {
            $this->persistItem($item);
        }
    }

    /**
     * @param \FeedIo\FeedInterface $feed
     */
    protected function persistFeed(\FeedIo\FeedInterface $feed) : void
    {
        $this->getLogger()->info("storing feed {$feed->getLink()}");
        // SQL Stuff
        //$this->getPdo()->exec('INSERT / UPDATE');
    }

    /**
     * @param \FeedIo\Feed\ItemInterface $item
     */
    protected function persistItem(\FeedIo\Feed\ItemInterface $item) : void
    {
        $this->getLogger()->info("storing item {$item->getTitle()}");
        // SQL Stuff
        // $this->getPdo()->exec('INSERT INTO ...');
    }

    /**
     * @param \FeedIo\Async\Request $request
     * @param Exception $exception
     */
    public function handleError(\FeedIo\Async\Request $request, \Exception $exception): void
    {
        $this->getLogger()->warning("Error reading {$request->getUrl()}  : {$exception->getMessage()}");
    }

}

$logger = (new FeedIo\Factory\Builder\MonologBuilder())->getLogger();
$pdo = new PDO('sqlite:memory:');
$callback = new PdoCallback($pdo, $logger);

$feedIo = new \FeedIo\FeedIo(new \FeedIo\Adapter\Guzzle\Client(new \GuzzleHttp\Client()), $logger);

$requests = [
    new FeedIo\Async\Request('https://jsonfeed.org/feed.json'),
    new FeedIo\Async\Request('https://jsonfeed.org/xml/rss.xml'),
    new FeedIo\Async\Request('https://packagist.org/feeds/releases.rss'),
    new FeedIo\Async\Request('https://packagist.org/feeds/packages.rss'),
    new FeedIo\Async\Request('https://debril.org/feed/'),
    new FeedIo\Async\Request('https://localhost:8000'),
];

$feedIo->readAsync($requests, $callback);
