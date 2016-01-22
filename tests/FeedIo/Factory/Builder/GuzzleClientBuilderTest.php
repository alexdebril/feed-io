<?php
namespace FeedIo\Factory\Builder;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class GuzzleClientBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testGetMainClassName()
    {
        $builder = new GuzzleClientBuilder();
        $this->assertEquals('\GuzzleHttp\Client', $builder->getMainClassName());
    }
    
    public function testGetPackageName()
    {
        $builder = new GuzzleClientBuilder();
        $this->assertEquals('guzzlehttp/guzzle', $builder->getPackageName());
    }
    
    public function testGetClient()
    {
        $builder = new GuzzleClientBuilder();
        $this->assertInstanceOf('\FeedIo\Adapter\ClientInterface', $builder->getClient());
    }

}
