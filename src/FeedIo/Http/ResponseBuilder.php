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
     * @var int $maxAge max-age in seconds
     */
    protected $maxAge;

    /**
     * @var bool $public is the response public
     */
    protected $public;

    /**
     * @param int $maxAge
     * @param bool $public
     */
    public function __construct(int $maxAge = 600, bool $public = true)
    {
        $this->maxAge = $maxAge;
        $this->public = $public;
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
            'Cache-Control' => ($this->public ? 'public':'private') . ", max-age={$this->maxAge}",
            'Last-Modified' => $feed->getLastModified()->format(\DateTime::RSS),
        ];

        return new Response(200, $headers, $formatter->toString($feed));
    }
}
