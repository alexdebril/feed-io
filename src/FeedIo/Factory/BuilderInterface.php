<?php

declare(strict_types=1);

namespace FeedIo\Factory;

/**
 * @package FeedIo
 */
interface BuilderInterface
{
    /**
     * This method MUST return the name of the main class
     * @return string
     */
    public function getMainClassName(): string;

    /**
     * This method MUST return the name of the package name
     * @return string
     */
    public function getPackageName(): string;
}
