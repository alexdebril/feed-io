<?php
namespace FeedIo;

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
            throw $e;
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
                'link' => $feed->getLink(),
            )
        );

        $this->assertInstanceOf('\DateTime', $feed->getLastModified());

        foreach ($feed as $item) {
            $this->performItemAssertions($item);
        }
    }

    protected function performItemAssertions(\FeedIo\Feed\ItemInterface $item)
    {
        $this->assertInstanceOf('\DateTime', $item->getLastModified());
        $this->performStringAssertions(
            array(
                'title' => $item->getTitle(),
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

        foreach ($urls as $url) {
            $out[] = array($url);
        }

        return $out;
    }

    protected function getUrls()
    {
        $localhost = array(

            'http://127.0.0.1:8080/feed-io/tests/samples/expected-atom.xml',
            'http://127.0.0.1:8080/feed-io/tests/samples/sample-atom.xml',
            'http://127.0.0.1:8080/feed-io/tests/samples/sample-atom-html.xml',
            'http://127.0.0.1:8080/feed-io/tests/samples/rss/expected-rss.xml',
            'http://127.0.0.1:8080/feed-io/tests/samples/rss/sample-rss.xml',
            'http://127.0.0.1:8080/feed-io/tests/samples/sample-rdf.xml',
        );

        $urls = array(
            'http://feeds.bbci.co.uk/news/rss.xml?edition=uk',
            'http://feeds.feedburner.com/dailyjs',
            'http://feeds.feedburner.com/HighScalability',
            'http://feeds.feedburner.com/symfony/blog',
            'http://feeds.mashable.com/Mashable',
            'http://feeds.slate.com/slate',
            'http://feeds.wired.com/wired/index',
            'http://feeds2.feedburner.com/blogspot/Egta',
            'http://feeds2.feedburner.com/LeJournalduGeek',
            'http://feeds2.feedburner.com/Webappers',
            'http://liberation.fr.feedsportal.com/c/32268/fe.ed/rss.liberation.fr/rss/44/',
            'http://liberation.fr.feedsportal.com/c/32268/fe.ed/rss.liberation.fr/rss/54/',
            'http://liberation.fr.feedsportal.com/c/32268/fe.ed/rss.liberation.fr/rss/latest/',
            'http://linuxfr.org/journaux.atom',
            'http://php.net/feed.atom',
            'http://pipes.yahoo.com/pipes/pipe.run?_id=647030be6aceb6d005c3775a1c19401c&_render=rss',
            'http://rss.lemonde.fr/c/205/f/3050/index.rss',
            'http://rss.slashdot.org/Slashdot/slashdot',
            'http://rue89.feedsportal.com/c/33822/f/608948/index.rss',
            'http://what-if.xkcd.com/feed.atom',
            'http://www.debian.org/News/news',
            'http://www.gizmodo.fr/feed',
            'http://www.larvf.com/rss/article/10135',
            'http://www.lemonde.fr/sciences/rss_full.xml',
            'http://www.lemonde.fr/technologies/rss_full.xml',
            'http://www.metalorgie.com/feed/news',
            'http://www.sitepoint.com/feed/',
            'http://www.slate.fr/rss.xml',
            'http://xkcd.com/rss.xml',
        );

        return $this->isLocalhostUp() ? array_merge($localhost, $urls) : $urls;
    }

    /**
     *
     */
    protected function isLocalhostUp()
    {
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->get('http://127.0.0.1:8080/feed-io/tests/');

            return 200 === (int) $response->getStatusCode();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}
