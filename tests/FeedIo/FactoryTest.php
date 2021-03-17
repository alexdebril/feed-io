<?php
namespace FeedIo;

use FeedIo\Factory\BuilderInterface;
use \PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testCheckDependency()
    {
        $factory = new Factory();
        $this->assertTrue($factory->checkDependency(
            $this->getBuilder('stdClass', 'php/php')
        ));
    }

    public function testCheckMissingDependency()
    {
        $factory = new Factory();
        $this->expectException('\FeedIo\Factory\MissingDependencyException');
        $factory->checkDependency(
            $this->getBuilder('IDontExist', 'php/php')
        );
    }

    public function testExtractConfig()
    {
        $config = ['foo' => 'bar'];
        $builderConfig = ['config' => $config];
        $factory = new Factory();
        $this->assertEquals($config, $factory->extractConfig($builderConfig));
    }

    public function testExtractEmptyConfig()
    {
        $builderConfig = [];
        $factory = new Factory();
        $this->assertEquals([], $factory->extractConfig($builderConfig));
    }
    public function testCreate()
    {
        $factory = Factory::create();
        $this->assertInstanceOf('\FeedIo\Factory', $factory);
    }

    public function testGetFeedIoAfterCreate()
    {
        $factory = Factory::create();
        $feedIo = $factory->getFeedIo();
        $this->assertInstanceOf('\FeedIo\FeedIo', $feedIo);
    }

    public function testGetFeedIoAfterCreateWithCustomConfig()
    {
        $factory = Factory::create(
            [
                'builder' => 'Monolog',
                'config' => [
                    'foo' => true,
                    'handlers' => [
                        [
                            'class' => 'Monolog\Handler\NullHandler',
                            'params' => [],
                        ],
                    ],
                ],
            ],
            [
                'builder' => 'GuzzleClient',
                'config' => [
                    'foo' => false,
                ],
            ]
        );

        $feedIo = $factory->getFeedIo();
        $this->assertInstanceOf('\FeedIo\FeedIo', $feedIo);
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

class ExternalBuilder implements BuilderInterface
{
    public function __construct(array $config)
    {
    }

    /**
     * @inheritDoc
     */
    public function getMainClassName() : string
    {
        return 'main';
    }

    /**
     * @inheritDoc
     */
    public function getPackageName() : string
    {
        return 'package';
    }
}
