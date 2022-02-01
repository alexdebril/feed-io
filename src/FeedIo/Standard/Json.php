<?php

declare(strict_types=1);

namespace FeedIo\Standard;

use FeedIo\Formatter\JsonFormatter;
use FeedIo\FormatterInterface;
use FeedIo\Reader\Document;
use FeedIo\StandardAbstract;

class Json extends StandardAbstract
{
    public const SYNTAX_FORMAT = 'Json';

    protected array $mandatoryFields = ['version', 'title', 'items'];

    /**
     * @param Document $document
     * @return bool
     */
    public function canHandle(Document $document): bool
    {
        return $document->isJson();
    }

    /**
     * @return FormatterInterface
     */
    public function getFormatter(): FormatterInterface
    {
        return new JsonFormatter();
    }
}
