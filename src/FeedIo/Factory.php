<?php declare(strict_types=1);
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
     * @var \FeedIo\Factory\ClientBuilderInterface
     */
    protected $clientBuilder;

    /**
     * @var \FeedIo\Factory\LoggerBuilderInterface
     */
    protected $loggerBuilder;

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

    ) : Factory {
        $factory = new static();

        $factory->setClientBuilder(
            $factory->getBuilder($clientConfig['builder'], $factory->extractConfig($clientConfig))
        )
            ->setLoggerBuilder(
                $factory->getBuilder($loggerConfig['builder'], $factory->extractConfig($loggerConfig))
            );


        return $factory;
    }

    /**
     * @param ClientBuilderInterface $clientBuilder
     * @return Factory
     */
    public function setClientBuilder(ClientBuilderInterface $clientBuilder) : Factory
    {
        $this->clientBuilder = $clientBuilder;

        return $this;
    }

    /**
     * @param string $builder
     * @param array $args
     * @return BuilderInterface
     */
    public function getBuilder(string $builder, array $args = []) : BuilderInterface
    {
        $class = "\\FeedIo\\Factory\\Builder\\{$builder}Builder";

        if (!class_exists($class)) {
            $class = $builder;
        }

        $reflection = new \ReflectionClass($class);

        // Pass args only if constructor has
        return $reflection->newInstanceArgs([$args]);
    }

    /**
     * @param $builderConfig
     *
     * @return array
     */
    public function extractConfig(array $builderConfig) : array
    {
        return isset($builderConfig['config']) ? $builderConfig['config'] : [];
    }

    /**
     * @return \FeedIo\FeedIo
     */
    public function getFeedIo() : FeedIo
    {
        return new FeedIo(
            $this->clientBuilder->getClient(),
            $this->loggerBuilder->getLogger()
        );
    }

    /**
     * @param LoggerBuilderInterface $loggerBuilder
     *
     * @return Factory
     */
    public function setLoggerBuilder(LoggerBuilderInterface $loggerBuilder) : Factory
    {
        $this->loggerBuilder = $loggerBuilder;

        return $this;
    }

    /**
     * @param  BuilderInterface $builder
     *
     * @return boolean true if the dependency is met
     */
    public function checkDependency(BuilderInterface $builder) : bool
    {
        if (!class_exists($builder->getMainClassName())) {
            $message = "missing {$builder->getPackageName()}, please install it using composer : composer require {$builder->getPackageName()}";
            throw new MissingDependencyException($message);
        }

        return true;
    }
}
