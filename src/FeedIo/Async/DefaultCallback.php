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

class DefaultCallback implements CallbackInterface
{
    /**
     * @inheritDoc
     */
    public function process(Result $result): void
    {
        echo "I processed {$result->getUrl()} \n";
    }
}
