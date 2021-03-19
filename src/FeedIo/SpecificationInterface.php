<?php declare(strict_types=1);


namespace FeedIo;

use Psr\Log\LoggerInterface;
use Traversable;
use FeedIo\Reader\FixerSet;
use FeedIo\Rule\DateTimeBuilderInterface;

interface SpecificationInterface
{
    public function getFixerSet(): FixerSet;

    public function getDateTimeBuilder(): DateTimeBuilderInterface;

    public function newParser(string $format, StandardAbstract $standard): ParserAbstract;

    public function getStandard(string $name): StandardAbstract;

    public function getAllStandards(): array;
}
