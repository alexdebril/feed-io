<?php

declare(strict_types=1);

namespace FeedIo\Factory\Builder;

use FeedIo\Factory\LoggerBuilderInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

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
        $this->loggerName = isset($config['name']) ? $config['name'] : $this->loggerName;

        $this->handlersConfig = isset($config['handlers']) ? $config['handlers'] : $this->handlersConfig;
    }

    /**
     * @inheritdoc
     */
    public function getLogger(): LoggerInterface
    {
        $logger = new Logger($this->loggerName);

        foreach ($this->handlersConfig as $config) {
            $handler = $this->newHandler($config['class'], $config['params']);
            $logger->pushHandler($handler);
        }

        return $logger;
    }

    /**
     * @param string $class
     * @param array $params
     * @return \Monolog\Handler\HandlerInterface
     */
    public function newHandler(string $class, array $params = []): HandlerInterface
    {
        $reflection = new \ReflectionClass($class);

        if (! $reflection->implementsInterface('Monolog\Handler\HandlerInterface')) {
            throw new \InvalidArgumentException();
        }

        return $reflection->newInstanceArgs($params);
    }

    /**
     * This method MUST return the name of the main class
     * @return string
     */
    public function getMainClassName(): string
    {
        return 'Monolog\Logger';
    }

    /**
     * This method MUST return the name of the package name
     * @return string
     */
    public function getPackageName(): string
    {
        return 'monolog/monolog';
    }
}
