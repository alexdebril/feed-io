<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13/12/14
 * Time: 18:49
 */

namespace FeedIo\StandardFormatter;

use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Rss;

use PHPUnit\Framework\TestCase;

class RssTest extends FormatterTestAbstract
{
    public const SAMPLE_FILE = 'rss/expected-rss.xml';

    /**
     * @return StandardAbstract
     */
    protected function newStandard()
    {
        return new Rss(
            new DateTimeBuilder()
        );
    }
}
