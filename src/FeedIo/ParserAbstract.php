<?php

declare(strict_types=1);

namespace FeedIo;

use FeedIo\Parser\UnsupportedFormatException;
use FeedIo\Reader\Document;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use Psr\Log\LoggerInterface;

/**
 * Parses a document if its format matches the parser's standard
 *
 * Depends on :
 *  - FeedIo\StandardAbstract
 *  - Psr\Log\LoggerInterface
 *
 */
abstract class ParserAbstract
{
    public function __construct(
        protected StandardAbstract $standard,
        protected LoggerInterface $logger
    ) {
    }

    public function parse(Document $document, FeedInterface $feed): FeedInterface
    {
        if (!$this->standard->canHandle($document)) {
            throw new UnsupportedFormatException('this is not a supported format');
        }

        $this->checkBodyStructure($document, $this->standard->getMandatoryFields());
        $this->parseContent($document, $feed);

        return $feed;
    }

    public function getStandard(): StandardAbstract
    {
        return $this->standard;
    }

    public function addValidItem(FeedInterface $feed, NodeInterface $item): ParserAbstract
    {
        if ($item instanceof ItemInterface) {
            $feed->add($item);
        }

        return $this;
    }

    abstract public function parseContent(Document $document, FeedInterface $feed): FeedInterface;

    abstract public function checkBodyStructure(Document $document, iterable $mandatoryFields): bool;
}
