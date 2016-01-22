<?php
namespace FeedIo\Factory\Builder;


class NullLoggerBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testGetMainClassName()
    {
        $builder = new NullLoggerBuilder();
        $this->assertEquals('\Psr\Log\NullLogger', $builder->getMainClassName());
    }
    
    public function testGetPackageName()
    {
        $builder = new NullLoggerBuilder();
        $this->assertEquals('psr/log', $builder->getPackageName());
    }
    
    public function testGetLogger()
    {
        $builder = new NullLoggerBuilder();
        $logger = $builder->getLogger();
        $this->assertInstanceOf('\Psr\Log\NullLogger', $logger);
    }

}
