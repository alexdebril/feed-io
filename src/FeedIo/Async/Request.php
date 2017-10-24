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

use FeedIo\Adapter\ResponseInterface;

class Request
{

    /**
     * @var string
     */
    protected $url;

    /**
     * @var \DateTime
     */
    protected $modifiedSince;

    /**
     * @var \FeedIo\Adapter\ResponseInterface
     */
    protected $response;

    /**
     * Request constructor.
     * @param $url
     * @param $modifiedSince
     */
    public function __construct(string $url, \DateTime $modifiedSince = null)
    {
        $this->url = $url;
        $this->modifiedSince = $modifiedSince ?? new \DateTime('@0');
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedSince(): \DateTime
    {
        return $this->modifiedSince;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }
}
