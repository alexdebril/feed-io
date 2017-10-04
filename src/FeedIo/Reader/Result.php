<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Reader;

use FeedIo\Adapter\ResponseInterface;
use FeedIo\FeedInterface;

/**
 * Result of the read() operation
 *
 * a Result instance holds the following :
 *
 * - the Feed instance
 * - Date and time of the request
 * - value of the 'modifiedSince' header sent throught the request
 * - the raw response
 * - the DOM document
 * - URL of the feed
 */
class Result
{

    /**
     * @var \DateTime
     */
    protected $modifiedSince;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var \FeedIo\FeedInterface
     */
    protected $feed;

    /**
     * @var \FeedIo\Adapter\ResponseInterface
     */
    protected $response;

    /**
     * @var Document
     */
    protected $document;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param Document      $document
     * @param FeedInterface     $feed
     * @param \DateTime         $modifiedSince
     * @param ResponseInterface $response
     * @param $url
     */
    public function __construct(
        Document $document,
        FeedInterface $feed,
        \DateTime $modifiedSince,
        ResponseInterface $response,
        string $url
    ) {
        $this->date = new \DateTime();
        $this->document = $document;
        $this->feed = $feed;
        $this->modifiedSince = $modifiedSince;
        $this->response = $response;
        $this->url = $url;
    }

    /**
     * @return \DateTime
     */
    public function getDate() : \DateTime
    {
        return $this->date;
    }

    /**
     * @return Document
     */
    public function getDocument() : Document
    {
        return $this->document;
    }

    /**
     * @return FeedInterface
     */
    public function getFeed() : FeedInterface
    {
        return $this->feed;
    }

    /**
     * @return \DateTime|null
     */
    public function getModifiedSince() : ? \DateTime
    {
        return $this->modifiedSince;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse() : ResponseInterface
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }
}
