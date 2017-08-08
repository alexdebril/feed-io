<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Standard;

use FeedIo\Formatter\JsonFormatter;
use FeedIo\FormatterInterface;
use FeedIo\Reader\Document;
use FeedIo\StandardAbstract;

class Json extends StandardAbstract
{
    const SYNTAX_FORMAT = 'Json';

    protected $mandatoryFields = ['version', 'title', 'items'];

    /**
     * @param Document $document
     * @return bool
     */
    public function canHandle(Document $document) : bool
    {
        return $document->isJson();
    }

    /**
     * @return FormatterInterface
     */
    public function getFormatter() : FormatterInterface
    {
        return new JsonFormatter();
    }
}
