<?php

declare(strict_types=1);

namespace FeedIo;

use FeedIo\Reader\FixerSet;
use FeedIo\Rule\DateTimeBuilderInterface;

interface SpecificationInterface
{
    public function getFixerSet(): FixerSet;

    public function getDateTimeBuilder(): DateTimeBuilderInterface;

    public function newParser(string $format, StandardAbstract $standard): ParserAbstract;

    public function addStandard(string $name, StandardAbstract $standardAbstract): self;

    public function getStandard(string $name): StandardAbstract;

    public function getAllStandards(): array;
}
