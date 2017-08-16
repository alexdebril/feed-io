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

use FeedIo\Adapter\ClientInterface;

/**
 * @package FeedIo
 */
interface ClientBuilderInterface extends BuilderInterface
{

    /**
     * This method MUST return a \FeedIo\Adapter\ClientInterface instance
     * @return \FeedIo\Adapter\ClientInterface
     */
    public function getClient() : ClientInterface;
}
