<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Http;

use FeedIo\FeedInterface;
use FeedIo\FormatterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class ResponseBuilder
{

    /**
     * @var bool $public is the response public
     */
    protected $public;

    /**
     * @var int $maxAge max-age in seconds
     */
    protected $maxAge;

    /**
     * @param bool $public
     * @param int $maxAge
     */
    public function __construct(bool $public = true, int $maxAge = 600)
    {
        $this->public = $public;
        $this->maxAge = $maxAge;
    }

    /**
     * @param  string $format
     * @param  FormatterInterface $formatter
     * @param  FeedInterface $feed
     * @return ResponseInterface
     */
    public function createResponse(string $format, FormatterInterface $formatter, FeedInterface $feed) : ResponseInterface
    {
        $headers = [
            'Content-Type' => ($format === 'json') ? 'application/json':'application/xhtml+xml',
            'Cache-Control' => $this->public ? 'public':'private' . "max-age={$this->maxAge}",
            'Last-Modified' => $feed->getLastModified()->format(\DateTime::RSS);
        ];

        return new Response(200, $headers, $formatter->toString($feed));
    }

}
