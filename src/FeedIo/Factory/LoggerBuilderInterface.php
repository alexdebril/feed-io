<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Factory;

use Psr\Log\LoggerInterface;

/**
 * @package FeedIo
 */
interface LoggerBuilderInterface extends BuilderInterface
{

    /**
     * This method MUST return a valid PSR3 logger
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger() : LoggerInterface;
}
