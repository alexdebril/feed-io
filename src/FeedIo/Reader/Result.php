<?php

declare(strict_types=1);

namespace FeedIo\Reader;

use DateTime;
use FeedIo\Adapter\ResponseInterface;
use FeedIo\FeedInterface;
use FeedIo\Filter\Chain;
use FeedIo\Filter\Since;
use FeedIo\Reader\Result\UpdateStats;

/**
 * Result of the read() operation
 *
 * a Result instance holds the following :
 *
 * - the Feed instance
 * - Date and time of the request
 * - value of the 'modifiedSince' header sent through the request
 * - the raw response
 * - the DOM document
 * - URL of the feed
 */
class Result
{
    protected DateTime $date;

    protected ?UpdateStats $updateStats = null;

    public function __construct(
        protected Document $document,
        protected FeedInterface $feed,
        protected DateTime $modifiedSince,
        protected ResponseInterface $response,
        protected string $url
    ) {
        $this->date = new DateTime();
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getDocument(): Document
    {
        return $this->document;
    }

    public function getFeed(): FeedInterface
    {
        return $this->feed;
    }

    public function getItemsSince(DateTime $since = null): iterable
    {
        $filter = new Chain();
        $filter->add(new Since($since ?? $this->modifiedSince));

        return $filter->filter($this->getFeed());
    }

    public function getFilteredItems(Chain $filterChain): iterable
    {
        return $filterChain->filter($this->feed);
    }

    public function getModifiedSince(): ?DateTime
    {
        return $this->modifiedSince;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getNextUpdate(
        int $minDelay = UpdateStats::DEFAULT_MIN_DELAY,
        int $sleepyDelay = UpdateStats::DEFAULT_SLEEPY_DELAY,
        int $sleepyDuration = UpdateStats::DEFAULT_DURATION_BEFORE_BEING_SLEEPY,
        float $marginRatio = UpdateStats::DEFAULT_MARGIN_RATIO
    ): DateTime {
        $updateStats = $this->getUpdateStats();
        return $updateStats->computeNextUpdate($minDelay, $sleepyDelay, $sleepyDuration, $marginRatio);
    }

    public function getUpdateStats(): UpdateStats
    {
        if (is_null($this->updateStats)) {
            $this->updateStats = new UpdateStats($this->getFeed());
        }

        return $this->updateStats;
    }
}
