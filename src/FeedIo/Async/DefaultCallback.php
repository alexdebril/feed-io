<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Async;

use FeedIo\Reader\Result;
use \Psr\Log\LoggerInterface;

class DefaultCallback implements CallbackInterface
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function process(Result $result): void
    {
        $this->logger->info("feed processed : {$result->getUrl()} - title : {$result->getFeed()->getTitle()}");
    }

    /**
     * @inheritDoc
     */
    public function handleError(Request $request, \Exception $exception) : void
    {
        $this->logger->warning("exception caught for {$request->getUrl()} : {$exception->getMessage()}");
    }
}
