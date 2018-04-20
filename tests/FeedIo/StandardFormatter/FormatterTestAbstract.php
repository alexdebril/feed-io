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

    public function setUp()
    {
        $this->standard = $this->newStandard();
    }

    public function testFormat()
    {
        $category = new Category();
        $category->setTerm('sample');
        $category->setLabel('sample');
        $category->setScheme('http://localhost');
        $date = new \DateTime('2014/12/01');
        $feed = new Feed();
        $feed->setTitle('sample title');
        $feed->setPublicId('http://localhost/item/1');
        $feed->setLastModified($date);
        $feed->setLink('http://localhost');
        $feed->setDescription('a sample feed');
        $feed->setLanguage('en');
        $feed->addCategory($category);
        
        $item = new Item();
        $item->setPublicId('http://localhost/item/1');
        $item->setLastModified($date);
        $item->setTitle('item title');
        $item->setDescription('A great description');
        $item->setLink('http://localhost/item/1');
        $item->set('author', 'name@domain.tld');
        $item->addCategory($category);
        $feed->add($item);

        $formatter = new XmlFormatter($this->standard);

        $document = $formatter->toDom($feed);
        $this->assertXmlStringEqualsXmlFile($this->getSampleFile(), $document->saveXML());
    }

    protected function getSampleFile()
    {
        return dirname(__FILE__)."/../../samples/".static::SAMPLE_FILE;
    }
}
