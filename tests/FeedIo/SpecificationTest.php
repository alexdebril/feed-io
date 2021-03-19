<?php


namespace FeedIo;

use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Atom;
use FeedIo\Standard\Json;
use PHPStan\Testing\TestCase;
use Psr\Log\NullLogger;

class SpecificationTest extends TestCase
{
    protected Specification $specification;

    protected function setUp(): void
    {
        $this->specification = new Specification(new NullLogger(), new DateTimeBuilder());
    }

    public function testFixerSet()
    {
        $this->assertInstanceOf('\FeedIo\Reader\FixerSet', $this->specification->getFixerSet());
    }

    public function testGetStandard()
    {
        $this->assertInstanceOf('FeedIo\Standard\Json', $this->specification->getStandard('json'));
    }

    public function testNewParser()
    {
        $parser = $this->specification->newParser('json', new Json($this->specification->getDateTimeBuilder()));
        $this->assertInstanceOf('FeedIo\Parser\JsonParser', $parser);

        $parser = $this->specification->newParser('atom', new Atom($this->specification->getDateTimeBuilder()));
        $this->assertInstanceOf('FeedIo\Parser\XmlParser', $parser);
    }
}
