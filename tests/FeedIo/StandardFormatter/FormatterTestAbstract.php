<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13/12/14
 * Time: 18:21
 */
namespace FeedIo\StandardFormatter;

use FeedIo\Feed;
use FeedIo\Feed\Item;
use FeedIo\Feed\Node\Category;
use FeedIo\Formatter\XmlFormatter;
use Psr\Log\NullLogger;
use \PHPUnit\Framework\TestCase;

abstract class FormatterTestAbstract extends TestCase
{

    /**
     * @var \FeedIo\StandardAbstract
     */
    protected $standard;

    const SAMPLE_FILE = '';

    /**
     * @return StandardAbstract
     */
    abstract protected function newStandard();

    public function setUp(): void
    {
        $this->standard = $this->newStandard();
    }

    public function testFormat()
    {
        $author = new Item\Author();
        $author->setEmail('name@domain.tld');
        $author->setName('author name');
        $category = new Category();
        $category->setTerm('sample');
        $category->setLabel('sample');
        $category->setScheme('http://localhost');
        $date = new \DateTime('2014/12/01');
        $feed = new Feed();
        $feed->setAuthor($author);
        $feed->setStyleSheet(new Feed\StyleSheet('http://localhost/style.xsl'));
        $feed->setTitle('sample title');
        $feed->set('itunes:title', 'sample title');
        $feed->setPublicId('http://localhost/item/1');
        $feed->setLastModified($date);
        $feed->setLink('http://localhost');
        $feed->setDescription('a sample feed');
        $feed->setLanguage('en');
        $feed->addCategory($category);
        $feed->addNS('itunes', 'http://www.itunes.com/dtds/podcast-1.0.dtd');


        $item = new Item();
        $item->setPublicId('http://localhost/item/1');
        $item->setLastModified($date);
        $item->setTitle('item title');
        $item->setContent('A great description');
        $item->setLink('http://localhost/item/1');
        $item->setAuthor($author);
        $item->addCategory($category);
        $item->set('custom', 'a sample value');
        $item->set('custom', 'another sample value');

        $feed->add($item);

        $formatter = new XmlFormatter($this->standard);

        $document = $formatter->toDom($feed);

        $this->assertXmlStringEqualsXmlFile($this->getSampleFile(), $document->saveXML());
    }

    public function testStyleSheet()
    {
        $feed = new Feed();
        $feed->setStyleSheet(new Feed\StyleSheet('http://localhost/style.xsl'));
        $formatter = new XmlFormatter($this->standard);

        $document = $formatter->toDom($feed);

        $this->assertStringContainsString('type="text/xsl" href="http://localhost/style.xsl"', $document->saveXML());
    }

    protected function getSampleFile()
    {
        return dirname(__FILE__)."/../../samples/".static::SAMPLE_FILE;
    }
}
