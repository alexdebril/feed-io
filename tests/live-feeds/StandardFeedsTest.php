<?php
namespace FeedIo;

use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Atom;

class StandardFeedsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FeedIo
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $guzzle = new \GuzzleHttp\Client();
        $client = new \FeedIo\Adapter\Guzzle\Client($guzzle);
        $logger = new \Psr\Log\NullLogger();
        
        $this->object = new FeedIo($client, $logger);
    }
    
    
    /**
     * @dataProvider provideUrls
     */
    public function testFeed($url)
    {
        try {
            $result = $this->object->read($url);
            $this->performAssertions($result);
        } catch (\FeedIo\Reader\ReadErrorException $e) {
            $this->markTestIncomplete("read error : {$e->getMessage()}");
        }
        
    }
    
    protected function performAssertions(\FeedIo\Reader\Result $result)
    {
        $feed = $result->getFeed();
        $this->assertInstanceOf('\FeedIo\FeedInterface', $feed);
        $this->performStringAssertions(
            array(
                'title' => $feed->getTitle(),
                'id' => $feed->getPublicId(),
                'link' => $feed->getLink(),
            )
        );
        
        $this->assertInstanceOf('\DateTime', $feed->getLastModified());
        
        foreach ( $feed as $item ) {
            $this->performItemAssertions($item);
        }
    }
    
    protected function performItemAssertions(\FeedIo\Feed\ItemInterface $item) 
    {
        $this->performStringAssertions(
            array(
                'title' => $item->getTitle(),
                'id' => $item->getPublicId(),
                'link' => $item->getLink(),
                'description' => $item->getDescription(),
            )
        );
    }
    
    protected function performStringAssertions(array $strings)
    {
        foreach ($strings as $name => $string) {
            $this->assertInternalType('string', $string, "$name must be a string");
            $this->assertTrue(strlen($string) > 0, "$name cannot be empty");
            $this->assertEncodingIsUtf8($string, $name);
        }
    }
   
    protected function assertEncodingIsUtf8($string, $name)
    {
        return $this->assertTrue(mb_check_encoding($string, 'utf-8'), "$name must be utf-8 encoded");
    }

    /**
     * @return array
     */
    public function provideUrls()
    {
        $urls = $this->getUrls();
        $out = array();
        
        foreach( $urls as $url ) {
            $out[] = array($url);
        }
        
        return $out;
    }
    
    protected function getUrls()
    {
        return array(
            
            'http://127.0.0.1:8080/feed-io/tests/samples/expected-atom.xml',
            'http://127.0.0.1:8080/feed-io/tests/samples/sample-atom.xml',          
            'http://127.0.0.1:8080/feed-io/tests/samples/sample-atom-html.xml',
            'http://127.0.0.1:8080/feed-io/tests/samples/rss/expected-rss.xml',
            'http://127.0.0.1:8080/feed-io/tests/samples/rss/sample-rss.xml',
        );
    }
}
