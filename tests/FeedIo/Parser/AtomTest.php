<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser;


use FeedIo\Feed;
use Psr\Log\NullLogger;

class AtomTest extends ParserTestAbstract
{

    const SAMPLE_FILE = 'sample-atom.xml';

    /**
     * @var \FeedIo\Parser\Atom
     */
    protected $object;

    /**
     * @return \FeedIo\ParserAbstract
     */
    public function getObject()
    {
        return new Atom(
            new DateTimeBuilder(),
            new NullLogger()
        );
    }

}
