<?php

declare(strict_types=1);

namespace FeedIo;

use FeedIo\Adapter\ClientInterface;
use FeedIo\Factory\Builder\GuzzleClientBuilder;
use FeedIo\Factory\Builder\MonologBuilder;
use FeedIo\Factory\Builder\NullLoggerBuilder;
use FeedIo\Factory\MissingDependencyException;
use FeedIo\Factory\LoggerBuilderInterface;
use FeedIo\Factory\ClientBuilderInterface;
use FeedIo\Factory\BuilderInterface;
use Psr\Log\LoggerInterface;

class Factory
{
    protected LoggerInterface $logger;

    protected ClientInterface $client;

    protected ClientBuilderInterface $clientBuilder;

    protected LoggerBuilderInterface $loggerBuilder;

    /**
     * @param array $loggerConfig
     * @param array $clientConfig
     * @return Factory
     */
    public static function create(
        array $loggerConfig = [
            'builder' => 'NullLogger',
            'config' => [],
        ],
        array $clientConfig = [
            'builder' => 'GuzzleClient',
            'config' => [],
        ]
    ): Factory {
        $factory = new self();

        $clientBuilder = new GuzzleClientBuilder($factory->extractConfig($clientConfig));
        $loggerConfig = $factory->getLoggerBuilder($loggerConfig['builder'], $factory->extractConfig($loggerConfig));

        $factory
            ->setClientBuilder($clientBuilder)
            ->setLoggerBuilder($loggerConfig);


        return $factory;
    }

    public function getLoggerBuilder(string $name, array $config): LoggerBuilderInterface
    {
        if (str_contains(strtolower($name), 'monolog')) {
            return new MonologBuilder($config);
        }
        return new NullLoggerBuilder();
    }

    public function setClientBuilder(ClientBuilderInterface $clientBuilder): Factory
    {
        $this->clientBuilder = $clientBuilder;

        return $this;
    }

    public function extractConfig(array $builderConfig): array
    {
        return isset($builderConfig['config']) ? $builderConfig['config'] : [];
    }

    public function getFeedIo(): FeedIo
    {
        return new FeedIo(
            $this->clientBuilder->getClient(),
            $this->loggerBuilder->getLogger()
        );
    }

    public function setLoggerBuilder(LoggerBuilderInterface $loggerBuilder): Factory
    {
        $this->loggerBuilder = $loggerBuilder;

        return $this;
    }

    public function checkDependency(BuilderInterface $builder): bool
    {
        if (!class_exists($builder->getMainClassName())) {
            $message = "missing {$builder->getPackageName()}, please install it using composer : composer require {$builder->getPackageName()}";
            throw new MissingDependencyException($message);
        }

        return true;
    }
}
