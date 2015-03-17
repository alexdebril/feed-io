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
                $feed->getTitle(),
                $feed->getPublicId(),
            )
        );
    }
    
    protected function performStringAssertions(array $strings)
    {
        foreach ($strings as $string) {
            $this->assertInternalType('string', $string);
        }
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
        );
    }
}
