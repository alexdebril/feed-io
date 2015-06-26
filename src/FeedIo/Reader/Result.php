<?php
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
     * @var \DomDocument
     */
    protected $document;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param \DOMDocument      $document
     * @param FeedInterface     $feed
     * @param \DateTime         $modifiedSince
     * @param ResponseInterface $response
     * @param $url
     */
    public function __construct(
        \DOMDocument $document,
        FeedInterface $feed,
        \DateTime $modifiedSince,
        ResponseInterface $response,
        $url
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
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return \DomDocument
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @return FeedInterface
     */
    public function getFeed()
    {
        return $this->feed;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedSince()
    {
        return $this->modifiedSince;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
