<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Async;

use FeedIo\Reader\Result;

interface CallbackInterface
{

    /**
     * @param Result $result
     */
    public function process(Result $result) : void;

    /**
     * @param Request $request
     * @param \Exception $exception
     */
    public function handleError(Request $request, \Exception $exception) : void;
}
