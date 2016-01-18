<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;

use FeedIo\Factory\MissingDependencyException;
use FeedIo\Factory\LoggerBuilderInterface;
use FeedIo\Factory\ClientBuilderInterface;
use FeedIo\Factory\BuilderInterface;

class Factory
{
   
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    
    /**
     * @var \FeedIo\Adapter\ClientInterface
     */
    protected $client;
    
    /**
     * @var FeedIo\Factory\LoggerBuilderInterface
     */
    protected $loggerBuilder;
    
    static public function create(
            array $loggerConfig = [
                    'builder' => 'NullLogger',
                    'config' => [],
            ],
            array $clientConfig = [
                    'builder' => 'GuzzleClient',
                    'config' => [],
            ]

    )
    {
        $factory = new static();
        $clientConfig['config'] = isset($clientConfig['config']) ? $clientConfig['config']:[];
        $loggerConfig['config'] = isset($loggerConfig['config']) ? $loggerConfig['config']:[];
        
        $factory->setClientBuilder(
                $factory->getBuilder($clientConfig['builder'], $clientConfig['config']))
                ->setLoggerBuilder(
                $factory->getBuilder($loggerConfig['builder'], $loggerConfig['config']));
                
                
        return $factory;        
    }
    
    /**
     * @return \FeedIo\FeedIo
     */
    public function getFeedIo()
    {
        return new FeedIo(
            $this->clientBuilder->getClient(),
            $this->loggerBuilder->getLogger()
            );
    }

    public function getBuilder($builder, array $args = [])
    {
        $class = "\FeedIo\Factory\Builder\\{$builder}Builder";
        
        if ( ! class_exists($class) ) {
            $class = $builder;
        }
        
        $reflection = new \ReflectionClass($class);
        
        return $reflection->newInstanceArgs($args);
    }
    
    public function setLoggerBuilder(LoggerBuilderInterface $loggerBuilder)
    {
        $this->loggerBuilder = $loggerBuilder;
        
        return $this;
    }
    
    public function setClientBuilder(ClientBuilderInterface $clientBuilder)
    {
        $this->clientBuilder = $clientBuilder;
    
        return $this;
    }
    
    /**
     * @param  BuilderInterface $builder
     * @return boolean true if the dependency is met
     */
    public function checkDependency(BuilderInterface $builder)
    {
        if ( ! class_exists($builder->getMainClassName()) ) {
            $message = "missing {$builder->getPackageName()}, please install it using composer : composer require {$builder->getPackageName()}";
            throw new MissingDependencyException($message);
        }
        
        return true;
    }
}
