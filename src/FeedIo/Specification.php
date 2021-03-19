<?php declare(strict_types=1);

namespace FeedIo;

use FeedIo\Parser\JsonParser;
use FeedIo\Parser\XmlParser;
use FeedIo\Reader\Fixer\HttpLastModified;
use FeedIo\Reader\Fixer\PublicId;
use FeedIo\Reader\FixerAbstract;
use FeedIo\Reader\FixerSet;
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Rule\DateTimeBuilderInterface;
use FeedIo\Standard\Atom;
use FeedIo\Standard\Json;
use FeedIo\Standard\Rdf;
use FeedIo\Standard\Rss;
use Psr\Log\LoggerInterface;
use OutOfRangeException;

class Specification implements SpecificationInterface
{
    protected array $standards;

    protected FixerSet $fixerSet;

    public function __construct(
        protected LoggerInterface $logger,
        protected ?DateTimeBuilderInterface $dateTimeBuilder = null,
    ) {
        if (is_null($this->dateTimeBuilder)) {
            $this->dateTimeBuilder = new DateTimeBuilder($this->logger);
        }

        $this->standards = [
            'json' => new Json($this->dateTimeBuilder),
            'atom' => new Atom($this->dateTimeBuilder),
            'rss' => new Rss($this->dateTimeBuilder),
            'rdf' => new Rdf($this->dateTimeBuilder),
        ];

        $this->fixerSet = new FixerSet();
        /** @var FixerAbstract $fixer */
        foreach ([new HttpLastModified(), new PublicId()] as $fixer) {
            $fixer->setLogger($this->logger);
            $this->fixerSet->add($fixer);
        }
    }

    public function getFixerSet(): FixerSet
    {
        return $this->fixerSet;
    }

    public function getDateTimeBuilder(): DateTimeBuilderInterface
    {
        return $this->dateTimeBuilder;
    }

    public function newParser(string $format, StandardAbstract $standard) : ParserAbstract
    {
        if (strtolower($format) === 'json') {
            return new JsonParser($standard, $this->logger);
        }
        return new XmlParser($standard, $this->logger);
    }

    public function getStandard(string $name): StandardAbstract
    {
        if (array_key_exists($name, $this->standards)) {
            return $this->standards[$name];
        }
        throw new OutOfRangeException("No standard found for $name");
    }

    public function getAllStandards(): array
    {
        return $this->standards;
    }
}
