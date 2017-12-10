<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace FeedIo\Adapter\Guzzle\Async;

use \FeedIo\Async\Request;

interface ReaderInterface
{

    /**
     * @param Request $request
     */
    public function handle(Request $request) : void;

    /**
     * @param Request $request
     * @param \Exception $e
     */
    public function handleError(Request $request, \Exception $e) : void;
}
