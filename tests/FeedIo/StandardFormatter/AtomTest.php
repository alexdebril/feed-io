<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13/12/14
 * Time: 18:32
 */

namespace FeedIo\StandardFormatter;

use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Atom;

use PHPUnit\Framework\TestCase;

class AtomTest extends FormatterTestAbstract
{
    public const SAMPLE_FILE = 'expected-atom.xml';

    /**
     * @return StandardAbstract
     */
    protected function newStandard()
    {
        return new Atom(
            new DateTimeBuilder()
        );
    }
}
