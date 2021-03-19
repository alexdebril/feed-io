<?php declare(strict_types=1);

namespace FeedIo\Adapter;

use \Psr\Http\Message\ResponseInterface;

class ServerErrorException extends HttpRequestException
{

    /**
     * @var ResponseInterface
     */
    protected $response;

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }
}
