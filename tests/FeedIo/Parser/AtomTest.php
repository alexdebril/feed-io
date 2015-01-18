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
use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Standard\Atom;

class AtomTest extends ParserTestAbstract
{

    const SAMPLE_FILE = 'sample-atom.xml';

    /**
     * @var \FeedIo\Parser\Atom
     */
    protected $object;

    /**
     * @return \FeedIo\StandardAbstract
     */
    public function getStandard()
    {
        return new Atom(new DateTimeBuilder());
    }

}
