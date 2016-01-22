<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Factory\Builder;

use FeedIo\Factory\LoggerBuilderInterface;
use Monolog\Logger;

/**
 * @package FeedIo
 */
class MonologBuilder implements LoggerBuilderInterface
{

    protected $loggerName = 'feed-io';

    protected $handlersConfig = [
        [
            'class' => 'Monolog\Handler\StreamHandler',
            'params' => ['php://stdout', Logger::DEBUG],
        ],
    ];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->loggerName = isset($config['name']) ? $config['name']:$this->loggerName;
        
        $this->handlersConfig = isset($config['handlers']) ? $config['handlers']:$this->handlersConfig;
    }

    /**
     * This method MUST return a valid PSR3 logger
     * @return \Monolog\Logger
     */
    public function getLogger()
    {
        $logger = new Logger($this->loggerName);
        
        foreach ( $this->handlersConfig as $config ) {
            $handler = $this->newHandler($config['class'], $config['params']);
            $logger->pushHandler($handler);
        }
        
        return $logger;
    }
    
    /**
     * @param string $class
     * @param array $params
     * @return Monolog\Handler\HandlerInterface
     */
    public function newHandler($class, array $params = [])
    {
        $reflection = new \ReflectionClass($class);
        
        if ( ! $reflection->implementsInterface('Monolog\Handler\HandlerInterface') ) {
            throw new \InvalidArgumentException();
        }
        
        return $reflection->newInstanceArgs($params);
    }

    /**
     * This method MUST return the name of the main class
     * @return string
     */
    public function getMainClassName()
    {
        return 'Monolog\Logger';
    }
    
    /**
     * This method MUST return the name of the package name
     * @return string
     */
    public function getPackageName()
    {
        return 'monolog/monolog';
    }
    
}
