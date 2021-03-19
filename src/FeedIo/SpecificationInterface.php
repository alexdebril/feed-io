<?php declare(strict_types=1);


namespace FeedIo;

use Traversable;
use FeedIo\Rule\DateTimeBuilderInterface;

interface SpecificationInterface
{
    public function getDateTimeBuilder(): DateTimeBuilderInterface;

    public function getStandard(string $name): StandardAbstract;

    public function listStandards(): Traversable;

    public function getAllStandards(): Traversable;
}
