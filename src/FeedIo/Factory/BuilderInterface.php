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

/**
 * @package FeedIo
 */
interface BuilderInterface
{

    /**
     * This method MUST return the name of the main class
     * @return string
     */
    public function getMainClassName() : string;
    
    /**
     * This method MUST return the name of the package name
     * @return string
     */
    public function getPackageName() : string;
}
