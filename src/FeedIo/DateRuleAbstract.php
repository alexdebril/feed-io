<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;


use FeedIo\Parser\DateTimeBuilder;

abstract class DateRuleAbstract extends RuleAbstract
{
    /**
     * @var \FeedIo\Parser\DateTimeBuilder
     */
    protected $dateTimeBuilder = null;

    /**
     * @param DateTimeBuilder $dateTimeBuilder
     * @return $this
     */
    public function setDateTimeBuilder(DateTimeBuilder $dateTimeBuilder)
    {
        $this->dateTimeBuilder = $dateTimeBuilder;

        return $this;
    }

    /**
     * @return DateTimeBuilder
     */
    public function getDateTimeBuilder()
    {
        if ( is_null($this->dateTimeBuilder) ) {
            throw new \UnexpectedValueException('date time builder should not be null');
        }

        return $this->dateTimeBuilder;
    }
}
