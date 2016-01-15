<?php
namespace FeedIo;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCheckDependency()
    {   
        $factory = new Factory();
        $this->assertTrue($factory->checkDependency(
            $this->getBuilder('stdClass', 'php/php')
        ));
    }

    /**
     * @expectedException FeedIo\Factory\MissingDependencyException
     */
    public function testCheckMissingDependency()
    {   
        $factory = new Factory();
        $factory->checkDependency(
            $this->getBuilder('IDontExist', 'php/php')
        );
    }
    
    public function testSetLoggerBuilder()
    {
        $factory = new Factory();
        $loggerBuilder = $this->getMockForAbstractClass('\FeedIo\Factory\LoggerBuilderInterface');
        
        $factory->setLoggerBuilder($loggerBuilder);
        $this->assertAttributeInstanceOf('\FeedIo\Factory\LoggerBuilderInterface', 'loggerBuilder', $factory);
    } 
    
    public function testSetClientBuilder()
    {
        $factory = new Factory();
        $clientBuilder = $this->getMockForAbstractClass('\FeedIo\Factory\ClientBuilderInterface');
            
        $factory->setClientBuilder($clientBuilder);
        $this->assertAttributeInstanceOf('\FeedIo\Factory\ClientBuilderInterface', 'clientBuilder', $factory);
        
    } 
    
    public function testGetFeedIo()
    {
        $factory = new Factory();
        $clientBuilder = $this->getMockForAbstractClass('\FeedIo\Factory\ClientBuilderInterface');
        $clientBuilder
            ->expects($this->once())
            ->method('getClient')
            ->will($this->returnValue(new \FeedIo\Adapter\Guzzle\Client(new \GuzzleHttp\Client())));
        
        $factory->setClientBuilder($clientBuilder);

        $loggerBuilder = $this->getMockForAbstractClass('\FeedIo\Factory\LoggerBuilderInterface');
        $loggerBuilder
            ->expects($this->once())
            ->method('getLogger')
            ->will($this->returnValue(new \Psr\Log\NullLogger));
        
        $factory->setLoggerBuilder($loggerBuilder);
        
        $this->assertInstanceOf('FeedIo\FeedIo', $factory->getFeedIo());
    }
    
    protected function getBuilder($className, $package)
    {
        $builder = $this->getMockForAbstractClass('\FeedIo\Factory\BuilderInterface');
        $builder->expects($this->any())->method('getMainClassName')->will($this->returnValue($className));
        
        $builder->expects($this->any())->method('getPackageName')->will($this->returnValue($package));
        
        return $builder;
    }
    
}
