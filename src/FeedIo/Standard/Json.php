<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Standard;


use FeedIo\Reader\Document;
use FeedIo\RuleSet;
use FeedIo\StandardAbstract;

class Json extends StandardAbstract
{

    const SYNTAX_FORMAT = 'Json';

    /**
     * @param Document $document
     * @return bool
     */
    public function canHandle(Document $document)
    {
        return $document->isJson();
    }

}