<?php

declare(strict_types=1);

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

        $this->standards = $this->getDefaultStandards();

        $this->fixerSet = new FixerSet();
        /** @var FixerAbstract $fixer */
        foreach ([new HttpLastModified(), new PublicId()] as $fixer) {
            $fixer->setLogger($this->logger);
            $this->fixerSet->add($fixer);
        }
    }

    /**
     * If you need to replace an existing standard with one of yours, you can extend the class and override this method
     * to redefine the list of default standards.
     *
     * @return array
     */
    protected function getDefaultStandards(): array
    {
        return [
            'json' => new Json($this->dateTimeBuilder),
            'atom' => new Atom($this->dateTimeBuilder),
            'rss' => new Rss($this->dateTimeBuilder),
            'rdf' => new Rdf($this->dateTimeBuilder),
        ];
    }

    /**
     * Adds a new standard to the set, unless you specify a name already taken like 'rss' so you'll overwrite it.
     *
     * @param string $name
     * @param StandardAbstract $standard
     * @return $this
     */
    public function addStandard(string $name, StandardAbstract $standard): self
    {
        $name = strtolower($name);
        $this->standards[$name] = $standard;

        return $this;
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

    public function getFixerSet(): FixerSet
    {
        return $this->fixerSet;
    }

    public function getDateTimeBuilder(): DateTimeBuilderInterface
    {
        return $this->dateTimeBuilder;
    }

    public function newParser(string $format, StandardAbstract $standard): ParserAbstract
    {
        if (strtolower($format) === 'json') {
            return new JsonParser($standard, $this->logger);
        }
        return new XmlParser($standard, $this->logger);
    }
}
