<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 11:57
 */

namespace FeedIo\Parser;

use FeedIo\Feed;
use FeedIo\Parser\XmlParser as Parser;
use FeedIo\Reader\Document;
use Psr\Log\NullLogger;
use PHPUnit\Framework\TestCase;

abstract class ParserTestAbstract extends TestCase
{
    /**
     * @var \FeedIo\ParserAbstract
     */
    protected $object;

    public const SAMPLE_FILE = '';

    /**
     * @return \FeedIo\StandardAbstract
     */
    abstract public function getStandard();

    public function setUp(): void
    {
        $standard = $this->getStandard();
        $this->object = new Parser($standard, new NullLogger());
    }

    public function testCanHandle()
    {
        $document = $this->buildDomDocument(static::SAMPLE_FILE);
        $this->assertTrue($this->object->getStandard()->canHandle($document));
    }

    public function testGetMainElement()
    {
        $document = $this->buildDomDocument(static::SAMPLE_FILE);
        $element = $this->object->getStandard()->getMainElement($document->getDOMDocument());
        $this->assertInstanceOf('\DomElement', $element);
    }

    public function testBuildFeedRuleSet()
    {
        $ruleSet = $this->object->getStandard()->buildFeedRuleSet();
        $this->assertInstanceOf('\FeedIo\RuleSet', $ruleSet);
    }

    public function testBuildItemRuleSet()
    {
        $ruleSet = $this->object->getStandard()->buildItemRuleSet();
        $this->assertInstanceOf('\FeedIo\RuleSet', $ruleSet);
    }

    public function testParseBody()
    {
        $document = $this->buildDomDocument(static::SAMPLE_FILE);
        $feed = $this->object->parse($document, new Feed());
        $this->assertInstanceOf('\FeedIo\Feed', $feed);

        $this->assertNotEmpty($feed->getTitle(), 'title must not be empty');
        $this->assertNotEmpty($feed->getLink(), 'link must not be empty');
        $this->assertNotEmpty($feed->getLastModified(), 'lastModified must not be empty');
        $this->assertTrue($feed->valid(), 'the feed must contain an item');

        $this->runCategoriesTest($feed);
        $item = $feed->current();
        $this->assertInstanceOf('\FeedIo\Feed\ItemInterface', $item);
        if ($item instanceof \FeedIo\Feed\ItemInterface) {
            $this->assertNotEmpty($item->getTitle());
            $this->assertNotEmpty($item->getContent());
            $this->assertNotEmpty($item->getPublicId());
            $this->assertNotEmpty($item->getLastModified());
            $this->assertNotEmpty($item->getLink());
            $this->assertCount(1, $item->getAllElements());
            $this->assertTrue($item->hasElement('extra'));
            $this->runCategoriesTest($item);
        }
    }

    protected function runCategoriesTest(\FeedIo\Feed\NodeInterface $node)
    {
        $categories = $node->getCategories();
        $this->assertCount(1, $categories);

        $category = $categories->current();
        $this->assertInstanceOf('\FeedIo\Feed\Node\CategoryInterface', $category);

        $this->assertNotEmpty($category->getTerm());
        $this->assertNotEmpty($category->getLabel());
    }

    /**
     * @param $filename
     * @return Document
     */
    protected function buildDomDocument($filename)
    {
        $file = dirname(__FILE__)."/../../samples/{$filename}";
        $domDocument = new \DOMDocument();
        $domDocument->load($file, LIBXML_NOBLANKS | LIBXML_COMPACT);

        return new Document($domDocument->saveXML());
    }
}
