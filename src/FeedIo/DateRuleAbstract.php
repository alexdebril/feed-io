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

use FeedIo\Rule\DateTimeBuilder;

abstract class DateRuleAbstract extends RuleAbstract
{
    /**
     * @var \FeedIo\Rule\DateTimeBuilder
     */
    protected $dateTimeBuilder = null;

    /**
     * @var string
     */
    protected $defaultFormat = \DateTime::RSS;

    /**
     * @param  \FeedIo\Rule\DateTimeBuilder $dateTimeBuilder
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
        if (is_null($this->dateTimeBuilder)) {
            throw new \UnexpectedValueException('date time builder should not be null');
        }

        return $this->dateTimeBuilder;
    }

    /**
     * @return string
     */
    public function getDefaultFormat()
    {
        return $this->defaultFormat;
    }

    /**
     * @param string $defaultFormat
     */
    public function setDefaultFormat($defaultFormat)
    {
        $this->defaultFormat = $defaultFormat;
    }
}
